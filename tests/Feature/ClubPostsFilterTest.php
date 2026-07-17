<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * La page « Vie du club » ne doit lister que les articles du club, c'est-a-dire
 * ceux qui ne sont rattaches a aucune discipline (blackball, carambole, snooker,
 * americain). Couvre le listing par defaut (/posts) et par periode.
 */
class ClubPostsFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_club_listing_returns_only_non_discipline_posts(): void
    {
        Post::factory()->create(['title' => 'Club (null)', 'discipline' => null]);
        Post::factory()->create(['title' => 'Club (0)', 'discipline' => 0]);
        Post::factory()->create(['title' => 'Blackball', 'discipline' => 1]);
        Post::factory()->create(['title' => 'Carambole', 'discipline' => 2]);

        $titles = collect($this->getJson('/api/v1/posts')->assertOk()->json('data'))
            ->pluck('title');

        $this->assertContains('Club (null)', $titles);
        $this->assertContains('Club (0)', $titles);
        $this->assertNotContains('Blackball', $titles);
        $this->assertNotContains('Carambole', $titles);
        $this->assertCount(2, $titles);
    }

    public function test_club_period_listing_excludes_discipline_posts(): void
    {
        $year = (int) now()->year;
        Post::factory()->create(['title' => 'Club recent', 'discipline' => null, 'year' => $year]);
        Post::factory()->create(['title' => 'Snooker recent', 'discipline' => 3, 'year' => $year]);

        $currentDecade = (int) floor($year / 10) * 10;
        $titles = collect(
            $this->getJson("/api/v1/posts/period/depuis_{$currentDecade}")->assertOk()->json('data')
        )->pluck('title');

        $this->assertContains('Club recent', $titles);
        $this->assertNotContains('Snooker recent', $titles);
    }
}
