<?php

namespace Tests\Feature;

use App\Models\Partenaire;
use App\Services\ClubDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Verifie que le tableau de bord n'agrege que des donnees reellement presentes
 * en base (pas d'approximation) et que la structure attendue par la vue est la.
 */
class ClubDashboardServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_metrics_expose_all_expected_sections(): void
    {
        $metrics = app(ClubDashboardService::class)->metrics();

        foreach (['articles', 'documents', 'partenaires', 'licencies', 'evenements', 'technique'] as $section) {
            $this->assertArrayHasKey($section, $metrics);
        }
    }

    public function test_it_counts_articles_and_groups_them_by_discipline(): void
    {
        // Insertion directe (query builder) volontaire : elle n'ecrit que sur des
        // colonnes reellement presentes en base, sans dependre de la fabrique Post
        // ni d'eventuels attributs par defaut du modele.
        $this->makePost('Blackball 1', 1);
        $this->makePost('Blackball 2', 1);
        $this->makePost('Carambole 1', 2);

        $articles = app(ClubDashboardService::class)->metrics()['articles'];

        $this->assertSame(3, $articles['total']);
        $this->assertSame(2, $articles['byDiscipline']['Blackball']);
        $this->assertSame(1, $articles['byDiscipline']['Carambole']);
    }

    public function test_partner_metric_only_counts_visible_partners(): void
    {
        Partenaire::create(['titre' => 'Actif', 'actif' => true]);
        Partenaire::create(['titre' => 'Inactif', 'actif' => false]);
        Partenaire::create([
            'titre' => 'Expire bientot',
            'actif' => true,
            'date_fin' => now()->addDays(10)->toDateString(),
        ]);

        $partenaires = app(ClubDashboardService::class)->metrics()['partenaires'];

        $this->assertSame(2, $partenaires['active']);
        $this->assertCount(1, $partenaires['expiringSoon']);
    }

    private function makePost(string $title, int $discipline): void
    {
        DB::table('posts')->insert([
            'title' => $title,
            'slug' => str($title)->slug()->value(),
            'excerpt' => 'Extrait de test',
            'content' => 'Contenu de test',
            'thumbnail' => 'test.webp',
            'discipline' => $discipline,
            'year' => 2026,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
