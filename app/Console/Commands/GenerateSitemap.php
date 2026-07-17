<?php

namespace App\Console\Commands;

use DOMDocument;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml (no external packages)';

    public function handle(): int
    {
        $urls = [];

        // Helper pour ajouter une URL
        $add = function (string $path, string $priority = '0.6', string $freq = 'weekly', ?string $lastmod = null) use (&$urls) {
            $urls[] = [
                'loc'      => URL::to($path),
                'priority' => $priority,
                'freq'     => $freq,
                'lastmod'  => $lastmod ?? now()->toDateString(),
            ];
        };

        /* 1) Pages statiques */
        $add('/', '1.0', 'daily');                    // Accueil
        $add('/contact', '0.5', 'monthly');           // Contact (GET)
        $add('/mentions-legales', '0.3', 'yearly');
        $add('/conditions-generales-utilisation', '0.3', 'yearly');
        $add('/politique-confidentialite', '0.3', 'yearly');
        $add('/cuescore', '0.4', 'monthly');

        /* 2) Sections par discipline (calendrier / document / classement + page index) */
        $sections = [
            'club'      => ['/club'],
            'blackball' => ['/blackball', '/blackball/calendrier', '/blackball/document', '/blackball/classement'],
            'carambole' => ['/carambole', '/carambole/calendrier', '/carambole/document', '/carambole/classement', '/carambole/classement/pdf'],
            'snooker'   => ['/snooker', '/snooker/calendrier', '/snooker/document', '/snooker/classement'],
            'americain' => ['/americain', '/americain/calendrier', '/americain/document', '/americain/classement'],
        ];
        foreach ($sections as $paths) {
            foreach ($paths as $p) {
                $add($p, Str::endsWith($p, ['/calendrier','/document','/classement','/classement/pdf']) ? '0.6' : '0.8', 'weekly');
            }
        }

        /* 3) Posts dynamiques par discipline (si table posts présente) */
        if (class_exists(\App\Models\Post::class) && Schema::hasTable('posts') && Schema::hasColumn('posts','slug')) {
            $hasDiscipline = Schema::hasColumn('posts','discipline');
            $hasUpdated    = Schema::hasColumn('posts','updated_at');
            $hasYear       = Schema::hasColumn('posts','year');

            $pathsByDiscipline = [
                'club'      => '/club',
                'blackball' => '/blackball',
                'carambole' => '/carambole',
                'snooker'   => '/snooker',
                'americain' => '/americain',
            ];

            // on parcourt par discipline pour fabriquer /{discipline}/{slug}
            foreach ($pathsByDiscipline as $discipline => $basePath) {
                // Seuls les articles publies figurent au sitemap (les brouillons
                // et publications programmees ne sont pas accessibles publiquement).
                $q = \App\Models\Post::query()->published()->select(['slug']);
                if ($hasUpdated) $q->addSelect('updated_at');
                if ($hasDiscipline) $q->where('discipline', $discipline);

                // itération mémoire-friendly
                foreach ($q->orderBy('id')->cursor() as $post) {
                    $slug = $post->slug;
                    if (!$slug) continue;
                    $lastmod = $hasUpdated && $post->updated_at ? $post->updated_at->toDateString() : now()->toDateString();
                    $add($basePath.'/'.$slug, '0.7', 'weekly', $lastmod);
                }
            }

            /* 4) Pages club par décennie : /club/annee/{decade} (si colonne year) */
            if ($hasYear) {
                $decades = \App\Models\Post::query()
                    ->published()
                    ->when($hasDiscipline, fn($q) => $q->where('discipline','club'))
                    ->whereNotNull('year')
                    ->pluck('year')
                    ->filter(fn($y) => is_numeric($y))
                    ->map(fn($y) => (int) floor(((int)$y) / 10) * 10)
                    ->unique()
                    ->sort()
                    ->values();

                foreach ($decades as $decade) {
                    $add('/club/annee/'.$decade, '0.5', 'monthly');
                }
            }
        }

        /* 5) Pages Cuescore dynamiques (si tables présentes) */
        foreach (['cuescore_equipes_nationales','cuescore_equipes_regionales'] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table,'id')) {
                $ids = \DB::table($table)->select('id')->orderBy('id')->pluck('id');
                foreach ($ids as $id) {
                    $add('/cuescore/'.$id, '0.4', 'monthly');
                }
            }
        }

        // 6) Génération XML
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $urlset = $doc->createElement('urlset');
        $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $doc->appendChild($urlset);

        foreach ($urls as $u) {
            $url = $doc->createElement('url');
            $url->appendChild($doc->createElement('loc', htmlspecialchars($u['loc'], ENT_XML1)));
            if (!empty($u['lastmod']))   $url->appendChild($doc->createElement('lastmod', $u['lastmod']));
            if (!empty($u['freq']))      $url->appendChild($doc->createElement('changefreq', $u['freq']));
            if (!empty($u['priority']))  $url->appendChild($doc->createElement('priority', $u['priority']));
            $urlset->appendChild($url);
        }

        $path = public_path('sitemap.xml');
        $doc->save($path);

        $this->info("✅ sitemap.xml généré : {$path}");
        return self::SUCCESS;
    }
}
