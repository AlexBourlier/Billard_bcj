<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Regroupe les documents sous un menu unique de premier niveau.
 *
 * Les documents etaient geres par un sous-menu « Documents officiels » sous
 * chaque discipline. Ils sont desormais tous accessibles via un seul ecran
 * (categorie affichee en label et filtrable). On retire donc les entrees par
 * discipline et on promeut l'entree « documents » en menu de premier niveau.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('admin_menu')
            ->whereIn('uri', ['documents-carambole', 'documents-snooker', 'documents-americain'])
            ->delete();

        DB::table('admin_menu')->where('uri', 'documents')->update([
            'parent_id' => 0,
            'title'     => 'Documents',
            'icon'      => 'icon-file-lines',
            'order'     => 15,
        ]);
    }

    public function down(): void
    {
        // Restauration best-effort : l'entree redevient un sous-menu Blackball.
        $blackball = DB::table('admin_menu')->where('title', 'Blackball')
            ->whereIn('parent_id', [0, null])->value('id');

        DB::table('admin_menu')->where('uri', 'documents')->update([
            'parent_id' => $blackball ?? 0,
            'title'     => 'Documents officiels',
            'order'     => 31,
        ]);
    }
};
