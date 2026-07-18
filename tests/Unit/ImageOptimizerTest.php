<?php

namespace Tests\Unit;

use App\Support\ImageOptimizer;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;

class ImageOptimizerTest extends TestCase
{
    private function isWebp(string $data): bool
    {
        // En-tete RIFF....WEBP.
        return strlen($data) > 12
            && substr($data, 0, 4) === 'RIFF'
            && substr($data, 8, 4) === 'WEBP';
    }

    public function test_redimensionne_les_images_trop_larges_et_encode_en_webp(): void
    {
        $file = UploadedFile::fake()->image('grande.jpg', 2000, 1000);

        $webp = ImageOptimizer::toWebp($file, 800, 82);

        $this->assertTrue($this->isWebp($webp), 'la sortie doit etre un WebP valide');

        $image = (new ImageManager(new GdDriver))->read($webp);
        $this->assertSame(800, $image->width(), 'largeur ramenee au maximum');
        $this->assertSame(400, $image->height(), 'proportions conservees');
    }

    public function test_nagrandit_pas_les_images_plus_petites_que_le_maximum(): void
    {
        $file = UploadedFile::fake()->image('petite.jpg', 300, 200);

        $webp = ImageOptimizer::toWebp($file, 800, 82);

        $image = (new ImageManager(new GdDriver))->read($webp);
        $this->assertSame(300, $image->width(), 'aucune mise a l\'echelle vers le haut');
        $this->assertSame(200, $image->height());
    }

    public function test_accepte_un_chemin_de_fichier(): void
    {
        $file = UploadedFile::fake()->image('depuis-chemin.jpg', 1200, 600);

        $webp = ImageOptimizer::toWebp($file->getRealPath(), 800, 82);

        $this->assertTrue($this->isWebp($webp));
        $image = (new ImageManager(new GdDriver))->read($webp);
        $this->assertSame(800, $image->width());
    }
}
