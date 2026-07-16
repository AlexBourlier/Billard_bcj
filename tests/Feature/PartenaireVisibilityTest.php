<?php

namespace Tests\Feature;

use App\Models\Partenaire;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifie la regle d'affichage public des partenaires (scope visible) :
 * seuls les partenaires actifs et dans leur fenetre de partenariat sont
 * exposes, et l'ordre d'affichage est respecte.
 */
class PartenaireVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_excludes_inactive_and_out_of_window_partners(): void
    {
        Partenaire::create(['titre' => 'Actif', 'actif' => true]);
        Partenaire::create(['titre' => 'Inactif', 'actif' => false]);
        Partenaire::create([
            'titre' => 'Pas encore commence',
            'actif' => true,
            'date_debut' => now()->addWeek()->toDateString(),
        ]);
        Partenaire::create([
            'titre' => 'Termine',
            'actif' => true,
            'date_fin' => now()->subDay()->toDateString(),
        ]);
        Partenaire::create([
            'titre' => 'Dans la fenetre',
            'actif' => true,
            'date_debut' => now()->subWeek()->toDateString(),
            'date_fin' => now()->addWeek()->toDateString(),
        ]);

        $titres = Partenaire::visible()->pluck('titre')->all();

        $this->assertContains('Actif', $titres);
        $this->assertContains('Dans la fenetre', $titres);
        $this->assertNotContains('Inactif', $titres);
        $this->assertNotContains('Pas encore commence', $titres);
        $this->assertNotContains('Termine', $titres);
    }

    public function test_it_orders_by_display_order_then_id(): void
    {
        $second = Partenaire::create(['titre' => 'B', 'actif' => true, 'ordre' => 2]);
        $first = Partenaire::create(['titre' => 'A', 'actif' => true, 'ordre' => 1]);
        $third = Partenaire::create(['titre' => 'C', 'actif' => true, 'ordre' => 2]);

        $ids = Partenaire::visible()->pluck('id')->all();

        // ordre 1 avant ordre 2 ; a ordre egal, le plus ancien id d'abord.
        $this->assertSame([$first->id, $second->id, $third->id], $ids);
    }
}
