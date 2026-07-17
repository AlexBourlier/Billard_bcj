<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Services\ClubDashboardService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Verifie le workflow de publication des articles : seuls les articles publies
 * et dont la date de publication est atteinte sont exposes publiquement ; les
 * brouillons et les publications programmees restent masques.
 */
class PostPublicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_list_excludes_drafts_and_scheduled_posts(): void
    {
        Post::factory()->create(['title' => 'Publie']);
        Post::factory()->draft()->create(['title' => 'Brouillon']);
        Post::factory()->scheduled()->create(['title' => 'Programme']);

        $titles = collect(
            $this->getJson('/api/v1/posts')->assertOk()->json('data')
        )->pluck('title');

        $this->assertContains('Publie', $titles);
        $this->assertNotContains('Brouillon', $titles);
        $this->assertNotContains('Programme', $titles);
    }

    public function test_a_draft_is_not_reachable_by_slug(): void
    {
        Post::factory()->draft()->create(['slug' => 'mon-brouillon']);

        $this->getJson('/api/v1/posts/slug/mon-brouillon')
            ->assertStatus(404);
    }

    public function test_a_scheduled_post_becomes_visible_once_its_date_is_reached(): void
    {
        $post = Post::factory()->scheduled()->create(['slug' => 'a-venir']);

        $this->getJson('/api/v1/posts/slug/a-venir')->assertStatus(404);

        // La date de publication est atteinte.
        $post->update(['published_at' => now()->subMinute()]);

        $this->getJson('/api/v1/posts/slug/a-venir')->assertOk();
    }

    public function test_published_scope_counts_are_reported_on_dashboard(): void
    {
        Post::factory()->count(2)->create();          // publies
        Post::factory()->draft()->create();           // brouillon
        Post::factory()->scheduled()->create();       // programme

        $articles = app(ClubDashboardService::class)->metrics()['articles'];

        $this->assertSame(4, $articles['total']);
        $this->assertSame(2, $articles['published']);
        $this->assertSame(1, $articles['drafts']);
        $this->assertSame(1, $articles['scheduled']);
    }
}
