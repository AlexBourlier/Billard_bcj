<?php

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\SiteSetting;
use App\Support\ApiCacheInvalidator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;

/**
 * Recompresse en WebP les images trop lourdes du site (articles, banniere,
 * logo) et regenere les miniatures manquantes.
 *
 * Contexte : certaines images « historiques » (ancien monolithe) sont stockees
 * brutes — des PNG de plusieurs Mo servies en pleine taille — et n'ont pas de
 * miniature WebP associee, si bien que les listes retombent sur l'original
 * lourd. La banniere et le logo, eux, ne sont pas compresses a l'upload.
 *
 * La commande est idempotente : elle ne touche qu'aux images depassant un
 * seuil de poids (ou non-WebP pour la banniere/logo) et ne regenere une
 * miniature que si elle est absente. Relancee, elle ignore ce qui est deja
 * optimise. L'option --dry-run analyse sans rien ecrire (le gain est calcule
 * reellement, l'encodage a lieu, mais rien n'est enregistre).
 */
class OptimizeImages extends Command
{
    protected $signature = 'images:optimize
        {--dry-run : Analyse et estime le gain sans rien modifier}
        {--threshold=400 : Poids (Ko) au-dela duquel une image principale est reencodee}';

    protected $description = 'Recompresse les images lourdes (articles, banniere, logo) en WebP et regenere les miniatures manquantes';

    // Reglages alignes sur le pipeline d'upload existant (voir AdminPostController
    // et AdminPartenairesController).
    private const POST_MAX_WIDTH = 1280;

    private const POST_QUALITY = 80;

    private const THUMB_WIDTH = 175;

    private const THUMB_QUALITY = 60;

    private const BANNER_MAX_WIDTH = 2000;

    private const BANNER_QUALITY = 82;

    private const LOGO_MAX_WIDTH = 800;

    private const LOGO_QUALITY = 85;

    private ImageManager $manager;

    private bool $dry = false;

    private int $threshold = 0;

    private int $savedBytes = 0;

    private int $processed = 0;

    private int $skipped = 0;

    private int $errors = 0;

    public function handle(): int
    {
        $this->manager = new ImageManager(new GdDriver);
        $this->dry = (bool) $this->option('dry-run');
        $this->threshold = max(0, (int) $this->option('threshold')) * 1024;

        if ($this->dry) {
            $this->warn('Mode simulation (--dry-run) : aucune ecriture ne sera effectuee.');
        }

        $disk = Storage::disk('public');

        $this->newLine();
        $this->info('== Images d\'articles ==');
        $this->optimizePosts($disk);

        $this->newLine();
        $this->info('== Banniere et logo du site ==');
        $this->optimizeSiteSettings($disk);

        $this->newLine();
        $this->info(sprintf(
            'Termine : %d optimisee(s), %d ignoree(s), %d erreur(s). Gain%s : %s.',
            $this->processed,
            $this->skipped,
            $this->errors,
            $this->dry ? ' estime' : '',
            $this->human($this->savedBytes)
        ));

        // Les images publiques (accueil, listes) sont mises en cache : on invalide
        // apres coup pour que les nouvelles versions soient servies immediatement.
        if (! $this->dry && $this->processed > 0) {
            app(ApiCacheInvalidator::class)->allPublic();
            $this->line('Cache public invalide.');
        }

        return $this->errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    private function optimizePosts(Filesystem $disk): void
    {
        Post::query()
            ->whereNotNull('thumbnail')
            ->where('thumbnail', '!=', '')
            ->orderBy('id')
            ->cursor()
            ->each(function (Post $post) use ($disk) {
                $path = $post->thumbnail; // ex : files/xxx.png

                if (! $disk->exists($path)) {
                    $this->warn("  #{$post->id} : fichier introuvable ({$path}) — ignore.");
                    $this->skipped++;

                    return;
                }

                $base = pathinfo($path, PATHINFO_FILENAME);
                $absPath = $disk->path($path);
                $size = @filesize($absPath) ?: 0;

                // 1) Miniature WebP : la generer si elle manque (gain principal
                //    pour les listes, qui retombent sinon sur l'image lourde).
                $thumbPath = 'thumbs/'.$base.'.webp';
                $thumbDone = false;
                if (! $disk->exists($thumbPath)) {
                    try {
                        if (! $this->dry) {
                            $thumb = $this->manager->read($absPath)->scale(width: self::THUMB_WIDTH);
                            $disk->put($thumbPath, (string) $thumb->toWebp(quality: self::THUMB_QUALITY));
                        }
                        $thumbDone = true;
                    } catch (\Throwable $e) {
                        $this->error("  #{$post->id} : miniature echouee — {$e->getMessage()}");
                        $this->errors++;
                    }
                }

                // 2) Image principale : reencodee en WebP si trop lourde.
                $mainDone = false;
                $newSize = $size;
                if ($size > $this->threshold) {
                    try {
                        $image = $this->manager->read($absPath);
                        if ($image->width() > self::POST_MAX_WIDTH) {
                            $image = $image->scale(width: self::POST_MAX_WIDTH);
                        }
                        $webp = (string) $image->toWebp(quality: self::POST_QUALITY);
                        $newSize = strlen($webp);
                        $newPath = 'files/'.$base.'.webp';

                        if (! $this->dry) {
                            $disk->put($newPath, $webp);
                            // Si l'extension change (png/jpg -> webp), on met a jour
                            // la reference sans toucher a updated_at (DB brute), puis
                            // on supprime l'ancien fichier.
                            if ($newPath !== $path) {
                                DB::table('posts')->where('id', $post->id)->update(['thumbnail' => $newPath]);
                                $disk->delete($path);
                            }
                        }

                        $this->savedBytes += max(0, $size - $newSize);
                        $mainDone = true;
                    } catch (\Throwable $e) {
                        $this->error("  #{$post->id} : image principale echouee — {$e->getMessage()}");
                        $this->errors++;
                    }
                }

                if ($mainDone || $thumbDone) {
                    $this->processed++;
                    $parts = [];
                    if ($mainDone) {
                        $parts[] = sprintf('image %s -> %s', $this->human($size), $this->human($newSize));
                    }
                    if ($thumbDone) {
                        $parts[] = 'miniature generee';
                    }
                    $this->line("  #{$post->id} ({$path}) : ".implode(', ', $parts));
                } else {
                    $this->skipped++;
                }
            });
    }

    private function optimizeSiteSettings(Filesystem $disk): void
    {
        $settings = SiteSetting::query()->get();

        if ($settings->isEmpty()) {
            $this->line('  Aucun reglage de site.');

            return;
        }

        foreach ($settings as $setting) {
            $this->optimizeSettingImage($setting, 'logo', self::LOGO_MAX_WIDTH, self::LOGO_QUALITY, $disk);
            $this->optimizeSettingImage($setting, 'banniere', self::BANNER_MAX_WIDTH, self::BANNER_QUALITY, $disk);
        }
    }

    private function optimizeSettingImage(SiteSetting $setting, string $column, int $maxWidth, int $quality, Filesystem $disk): void
    {
        $path = $setting->{$column}; // ex : img/xxx.png

        if (empty($path)) {
            return;
        }

        if (! $disk->exists($path)) {
            $this->warn("  {$column} : fichier introuvable ({$path}) — ignore.");
            $this->skipped++;

            return;
        }

        $absPath = $disk->path($path);
        $size = @filesize($absPath) ?: 0;
        $isWebp = str_ends_with(strtolower($path), '.webp');

        // Deja au format cible et suffisamment leger : rien a faire.
        if ($isWebp && $size <= $this->threshold) {
            $this->skipped++;

            return;
        }

        try {
            $image = $this->manager->read($absPath);
            if ($image->width() > $maxWidth) {
                $image = $image->scale(width: $maxWidth);
            }
            $webp = (string) $image->toWebp(quality: $quality);
            $newSize = strlen($webp);
            $base = pathinfo($path, PATHINFO_FILENAME);
            $newPath = 'img/'.$base.'.webp';

            if (! $this->dry) {
                $disk->put($newPath, $webp);
                if ($newPath !== $path) {
                    DB::table('site_settings')->where('id', $setting->id)->update([$column => $newPath]);
                    $disk->delete($path);
                }
            }

            $this->savedBytes += max(0, $size - $newSize);
            $this->processed++;
            $this->line("  {$column} ({$path}) : {$this->human($size)} -> {$this->human($newSize)}");
        } catch (\Throwable $e) {
            $this->error("  {$column} : echoue — {$e->getMessage()}");
            $this->errors++;
        }
    }

    private function human(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).' Mo';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024).' Ko';
        }

        return $bytes.' o';
    }
}
