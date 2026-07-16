<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Verifie le seeding des roles/permissions metier batis sur le RBAC natif
 * d'OpenAdmin. Point cle : chaque permission metier porte un `http_path`
 * (enforcement cote serveur) — masquer un bouton ne suffirait pas.
 */
class ClubRolesPermissionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_business_permissions_carry_an_http_path_for_server_enforcement(): void
    {
        $articles = DB::table('admin_permissions')->where('slug', 'content.articles')->first();

        $this->assertNotNull($articles, 'La permission content.articles doit exister.');
        $this->assertStringContainsString('/posts', $articles->http_path);
    }

    public function test_each_business_role_is_seeded(): void
    {
        $slugs = DB::table('admin_roles')->pluck('slug')->all();

        foreach (['redacteur', 'resp_partenaires', 'resp_documents', 'admin_site'] as $slug) {
            $this->assertContains($slug, $slugs);
        }
    }

    public function test_redacteur_is_limited_to_article_management(): void
    {
        $perms = $this->permissionSlugsForRole('redacteur');

        $this->assertContains('content.articles', $perms);
        // Un redacteur ne doit pas gerer les licencies ni les parametres.
        $this->assertNotContains('club.licencies', $perms);
        $this->assertNotContains('club.parametres', $perms);
    }

    public function test_admin_site_role_aggregates_every_business_permission(): void
    {
        $perms = $this->permissionSlugsForRole('admin_site');

        foreach ([
            'content.articles', 'content.partenaires', 'content.documents',
            'sport.classements', 'club.licencies', 'club.parametres',
        ] as $slug) {
            $this->assertContains($slug, $perms);
        }
    }

    /**
     * @return array<int, string>
     */
    private function permissionSlugsForRole(string $roleSlug): array
    {
        $roleId = DB::table('admin_roles')->where('slug', $roleSlug)->value('id');

        return DB::table('admin_role_permissions')
            ->join('admin_permissions', 'admin_permissions.id', '=', 'admin_role_permissions.permission_id')
            ->where('admin_role_permissions.role_id', $roleId)
            ->pluck('admin_permissions.slug')
            ->all();
    }
}
