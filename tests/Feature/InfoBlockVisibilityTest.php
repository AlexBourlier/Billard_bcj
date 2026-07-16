<?php

namespace Tests\Feature;

use App\Models\InfoBlock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifie la regle d'affichage public des blocs d'information (scope visible) :
 * actifs, dans leur fenetre de dates, les plus importants d'abord.
 */
class InfoBlockVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_only_returns_active_blocks_within_their_window(): void
    {
        InfoBlock::create(['titre' => 'Visible', 'niveau' => 'info', 'actif' => true]);
        InfoBlock::create(['titre' => 'Desactive', 'niveau' => 'info', 'actif' => false]);
        InfoBlock::create([
            'titre' => 'Futur',
            'niveau' => 'info',
            'actif' => true,
            'date_debut' => now()->addWeek()->toDateString(),
        ]);
        InfoBlock::create([
            'titre' => 'Expire',
            'niveau' => 'info',
            'actif' => true,
            'date_fin' => now()->subDay()->toDateString(),
        ]);

        $titres = InfoBlock::visible()->pluck('titre')->all();

        $this->assertSame(['Visible'], $titres);
    }

    public function test_it_orders_by_display_order_then_recent_first(): void
    {
        $b = InfoBlock::create(['titre' => 'B', 'niveau' => 'info', 'actif' => true, 'ordre' => 2]);
        $a = InfoBlock::create(['titre' => 'A', 'niveau' => 'info', 'actif' => true, 'ordre' => 1]);
        $c = InfoBlock::create(['titre' => 'C', 'niveau' => 'info', 'actif' => true, 'ordre' => 2]);

        $ids = InfoBlock::visible()->pluck('id')->all();

        // ordre croissant, puis id decroissant a ordre egal (le plus recent d'abord).
        $this->assertSame([$a->id, $c->id, $b->id], $ids);
    }
}
