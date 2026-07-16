<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rôles et permissions metier, batis sur le systeme RBAC natif d'OpenAdmin
 * (aucun second systeme). L'enforcement est cote serveur : chaque permission
 * porte un `http_path` verifie par le middleware Permission d'OpenAdmin — masquer
 * un bouton ne suffirait pas.
 *
 * Le role `administrator` (compte principal) n'est pas touche : il conserve
 * l'acces total (bypass isAdministrator d'OpenAdmin). Les nouveaux roles sont
 * simplement disponibles pour etre attribues a des benevoles.
 *
 * Migration idempotente (reperage par slug).
 */
return new class extends Migration
{
    /** Permissions metier : [nom, slug, chemins http (enforcement serveur)]. */
    private const PERMISSIONS = [
        ['Actualités',           'content.articles',    "/posts*"],
        ['Partenaires',          'content.partenaires', "/partenaires*"],
        ['Documents',            'content.documents',   "/documents*"],
        ['Classements CueScore', 'sport.classements',   "/cuescore-classements*\n/cuescore-mappings*"],
        ['Licenciés',            'club.licencies',      "/licencies*\n/license-import*"],
        ['Paramètres du site',   'club.parametres',     "/site-settings*\n/menus*\n/indices*\n/contacts*"],
    ];

    /** Roles : [nom, slug, permissions metier]. Les permissions de base
     *  (tableau de bord, profil) sont ajoutees a chaque role. */
    private const ROLES = [
        ['Rédacteur',               'redacteur',        ['content.articles']],
        ['Responsable partenaires', 'resp_partenaires', ['content.partenaires']],
        ['Responsable documents',   'resp_documents',   ['content.documents']],
        ['Administrateur du site',  'admin_site',       ['content.articles', 'content.partenaires', 'content.documents', 'sport.classements', 'club.licencies', 'club.parametres']],
    ];

    /** Permissions OpenAdmin par defaut requises par tout compte connecte. */
    private const BASE_PERMISSIONS = ['dashboard', 'auth.setting', 'auth.login'];

    /** Menus (par uri) rendus visibles pour chaque role. '/' = tableau de bord. */
    private const ROLE_MENUS = [
        'redacteur'        => ['/', 'posts'],
        'resp_partenaires' => ['/', 'partenaires'],
        'resp_documents'   => ['/', 'documents'],
        'admin_site'       => ['/', 'posts', 'partenaires', 'documents', 'cuescore-classements', 'licencies', 'site-settings'],
    ];

    public function up(): void
    {
        $now = now();

        // 1) Permissions metier.
        $permId = [];
        foreach (self::PERMISSIONS as [$name, $slug, $path]) {
            $existing = DB::table('admin_permissions')->where('slug', $slug)->value('id');
            $permId[$slug] = $existing ?: DB::table('admin_permissions')->insertGetId([
                'name' => $name, 'slug' => $slug, 'http_method' => '', 'http_path' => $path,
                'created_at' => $now, 'updated_at' => $now,
            ]);
        }
        foreach (self::BASE_PERMISSIONS as $slug) {
            $permId[$slug] = DB::table('admin_permissions')->where('slug', $slug)->value('id');
        }

        // 2) Roles + associations permissions.
        foreach (self::ROLES as [$name, $slug, $perms]) {
            $roleId = DB::table('admin_roles')->where('slug', $slug)->value('id')
                ?: DB::table('admin_roles')->insertGetId([
                    'name' => $name, 'slug' => $slug, 'created_at' => $now, 'updated_at' => $now,
                ]);

            foreach (array_merge(self::BASE_PERMISSIONS, $perms) as $pslug) {
                if (empty($permId[$pslug])) {
                    continue;
                }
                $has = DB::table('admin_role_permissions')
                    ->where('role_id', $roleId)->where('permission_id', $permId[$pslug])->exists();
                if (! $has) {
                    DB::table('admin_role_permissions')->insert([
                        'role_id' => $roleId, 'permission_id' => $permId[$pslug],
                        'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            }

            // 3) Menus visibles pour ce role.
            foreach (self::ROLE_MENUS[$slug] ?? [] as $uri) {
                $menuId = DB::table('admin_menu')->where('uri', $uri)->value('id');
                if ($menuId && ! DB::table('admin_role_menu')->where('role_id', $roleId)->where('menu_id', $menuId)->exists()) {
                    DB::table('admin_role_menu')->insert([
                        'role_id' => $roleId, 'menu_id' => $menuId,
                        'created_at' => $now, 'updated_at' => $now,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        $roleSlugs = array_column(self::ROLES, 1);
        $roleIds = DB::table('admin_roles')->whereIn('slug', $roleSlugs)->pluck('id');

        DB::table('admin_role_permissions')->whereIn('role_id', $roleIds)->delete();
        DB::table('admin_role_menu')->whereIn('role_id', $roleIds)->delete();
        DB::table('admin_role_users')->whereIn('role_id', $roleIds)->delete();
        DB::table('admin_roles')->whereIn('slug', $roleSlugs)->delete();
        DB::table('admin_permissions')->whereIn('slug', array_column(self::PERMISSIONS, 1))->delete();
    }
};
