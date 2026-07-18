<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use Tests\TestCase;

class OptimizeImagesTest extends TestCase
{
    use RefreshDatabase;

    private function png(int $w, int $h): string
    {
        return (string) (new ImageManager(new GdDriver))->create($w, $h)->fill('336699')->toPng();
    }

    public function test_convertit_une_image_lourde_en_webp_et_regenere_la_miniature(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('files/legacy.png', $this->png(1600, 1000));

        $post = Post::factory()->create(['thumbnail' => 'files/legacy.png']);

        // --threshold=0 : force le traitement meme d'une petite image de test.
        $this->artisan('images:optimize', ['--threshold' => 0])->assertSuccessful();

        $post->refresh();
        $this->assertSame('files/legacy.webp', $post->thumbnail);
        Storage::disk('public')->assertExists('files/legacy.webp');
        Storage::disk('public')->assertExists('thumbs/legacy.webp');
        // L'ancien fichier lourd est supprime apres conversion.
        Storage::disk('public')->assertMissing('files/legacy.png');
    }

    public function test_naffiche_rien_en_dry_run(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('files/legacy.png', $this->png(1600, 1000));
        $post = Post::factory()->create(['thumbnail' => 'files/legacy.png']);

        $this->artisan('images:optimize', ['--dry-run' => true, '--threshold' => 0])->assertSuccessful();

        // Simulation : rien n'est ecrit ni modifie.
        $post->refresh();
        $this->assertSame('files/legacy.png', $post->thumbnail);
        Storage::disk('public')->assertExists('files/legacy.png');
        Storage::disk('public')->assertMissing('files/legacy.webp');
        Storage::disk('public')->assertMissing('thumbs/legacy.webp');
    }

    public function test_ne_retraite_pas_une_image_deja_optimisee(): void
    {
        Storage::fake('public');
        // Une image deja au format cible et legere + sa miniature existante.
        Storage::disk('public')->put('files/ok.webp', 'SENTINELLE');
        Storage::disk('public')->put('thumbs/ok.webp', 'SENTINELLE');
        $post = Post::factory()->create(['thumbnail' => 'files/ok.webp']);

        $this->artisan('images:optimize')->assertSuccessful();

        // Idempotence : contenus intacts, chemin inchange (aucune reconversion).
        $post->refresh();
        $this->assertSame('files/ok.webp', $post->thumbnail);
        $this->assertSame('SENTINELLE', Storage::disk('public')->get('files/ok.webp'));
        $this->assertSame('SENTINELLE', Storage::disk('public')->get('thumbs/ok.webp'));
    }
}
