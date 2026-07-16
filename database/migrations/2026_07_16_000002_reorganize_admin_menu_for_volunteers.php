<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Rend le menu d'administration plus lisible pour des benevoles non techniques :
 * - intitules en francais clair (les entrees techniques d'OpenAdmin etaient en
 *   anglais) ;
 * - sections techniques (comptes, outils developpeur) regroupees et descendues
 *   en bas ; sections d'usage quotidien remontees en haut.
 *
 * On ne re-parente pas les entrees (uniquement titres et ordre) : aucun risque
 * de casser la navigation existante. Idempotent : un second passage ne trouve
 * plus les anciens libelles et ne fait rien.
 */
return new class extends Migration
{
    /** Renommage des entrees techniques (repere par uri, stable). */
    private const RENAME_BY_URI = [
        'auth/users'               => 'Utilisateurs',
        'auth/roles'               => 'Rôles',
        'auth/permissions'         => 'Permissions',
        'auth/menu'                => 'Menu (technique)',
        'auth/logs'                => 'Journal des actions',
        'helpers/scaffold'         => 'Générateur de code',
        'helpers/terminal/database'=> 'Terminal base de données',
        'helpers/terminal/artisan' => 'Commandes système',
        'helpers/routes'           => 'Liste des routes',
        'posts'                    => 'Actualités',
        'licencies'                => 'Licenciés',
        'license-import/batches'   => 'Import des licenciés',
        'cuescore-mappings'        => 'Licenciés ↔ CueScore',
        'menus'                    => 'Menus du site',
        'site-settings'            => 'Paramètres du site',
    ];

    /** Sections de premier niveau : [ancien titre => [nouveau titre, ordre]]. */
    private const SECTIONS = [
        'Admin'          => ['Administration technique', 90],
        'Helpers'        => ['Outils développeur', 91],
        'Administration' => ['Contenu du site', 2],
        'Système'        => ['Paramètres du site', 16],
        'Blackball'      => ['Blackball', 10],
        'Carambole'      => ['Carambole', 11],
        'Snooker'        => ['Snooker', 12],
        'Americain'      => ['Américain', 13],
        'Contacts'       => ['Contacts', 15],
    ];

    public function up(): void
    {
        foreach (self::RENAME_BY_URI as $uri => $title) {
            DB::table('admin_menu')->where('uri', $uri)->update(['title' => $title]);
        }

        foreach (self::SECTIONS as $old => [$new, $order]) {
            DB::table('admin_menu')
                ->where('title', $old)
                ->whereIn('parent_id', [0, null])
                ->where(function ($q) {
                    $q->whereNull('uri')->orWhere('uri', '');
                })
                ->update(['title' => $new, 'order' => $order]);
        }

        // "Classements CueScore" (deja en francais) : ordre.
        DB::table('admin_menu')->where('title', 'Classements CueScore')->update(['order' => 14]);
    }

    public function down(): void
    {
        // Restauration best-effort des libelles techniques d'origine.
        $revert = [
            'auth/users' => 'Users', 'auth/roles' => 'Roles', 'auth/permissions' => 'Permission',
            'auth/menu' => 'Menu', 'auth/logs' => 'Operation log', 'helpers/scaffold' => 'Scaffold',
            'helpers/terminal/database' => 'Database terminal', 'helpers/terminal/artisan' => 'Laravel artisan',
            'helpers/routes' => 'Routes', 'posts' => 'Posts', 'licencies' => 'Liste des licenciés',
            'license-import/batches' => 'Imports licences', 'cuescore-mappings' => 'Correspondances CueScore',
            'menus' => 'Menus',
        ];
        foreach ($revert as $uri => $title) {
            DB::table('admin_menu')->where('uri', $uri)->update(['title' => $title]);
        }
        DB::table('admin_menu')->where('title', 'Administration technique')->update(['title' => 'Admin', 'order' => 2]);
        DB::table('admin_menu')->where('title', 'Outils développeur')->update(['title' => 'Helpers', 'order' => 8]);
        DB::table('admin_menu')->where('title', 'Contenu du site')->update(['title' => 'Administration', 'order' => 13]);
    }
};
