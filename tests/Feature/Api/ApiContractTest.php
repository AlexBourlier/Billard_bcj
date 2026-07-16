<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tests de contrat : verifient que les principales reponses de l'API respectent
 * la structure documentee (data / meta / links / error) et les regles de
 * validation, independamment du contenu en base.
 */
class ApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_partners_endpoint_respects_standard_envelope(): void
    {
        $this->getJson('/api/v1/partenaires')
            ->assertOk()
            ->assertJsonStructure(['data', 'meta', 'links', 'error']);
    }

    public function test_home_endpoint_exposes_expected_sections(): void
    {
        $this->getJson('/api/v1/public/home')
            ->assertOk()
            ->assertJsonStructure([
                'data' => ['site_settings', 'menus', 'partners', 'featured_post'],
            ]);
    }

    public function test_contact_endpoint_validates_input(): void
    {
        $this->postJson('/api/v1/contact', [])
            ->assertStatus(422)
            ->assertJsonStructure(['message', 'errors']);
    }
}
