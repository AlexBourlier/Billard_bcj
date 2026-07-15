<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ajoute une colonne `category` a cuescore_rankings (mixte, feminin, junior...)
 * afin d'exposer la categorie comme champ a part entiere dans le CRUD admin,
 * au lieu de la laisser noyee dans le champ `name`.
 *
 * Backfill best-effort a partir des mots-cles presents dans le nom.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('cuescore_rankings', 'category')) {
            Schema::table('cuescore_rankings', function (Blueprint $table) {
                $table->string('category')->nullable()->after('team_category');
            });
        }

        foreach (DB::table('cuescore_rankings')->get() as $row) {
            $category = $this->guessCategory($row);
            if ($category !== null) {
                DB::table('cuescore_rankings')
                    ->where('id', $row->id)
                    ->update(['category' => $category]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('cuescore_rankings', 'category')) {
            Schema::table('cuescore_rankings', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    private function guessCategory(object $row): ?string
    {
        if (($row->ranking_type ?? null) === 'team' || ! empty($row->team_category)) {
            return 'Équipes';
        }

        $name = mb_strtolower((string) ($row->name ?? ''));

        $rules = [
            'mixte'     => 'Mixte',
            'féminin'   => 'Féminin',
            'feminin'   => 'Féminin',
            'junior'    => 'Junior (U18)',
            'u18'       => 'Junior (U18)',
            'u17'       => 'Junior (U18)',
            'espoir'    => 'Espoir (U23)',
            'u23'       => 'Espoir (U23)',
            'vétéran'   => 'Vétéran',
            'veteran'   => 'Vétéran',
            'master'    => 'Master',
            'benjamin'  => 'Benjamin (U15)',
            'u15'       => 'Benjamin (U15)',
            'top ligue' => 'Top ligue',
        ];

        if (str_contains($name, 'handi')) {
            return str_contains($name, 'debout') ? 'Handi debout' : 'Handi fauteuil';
        }

        foreach ($rules as $needle => $label) {
            if (str_contains($name, $needle)) {
                return $label;
            }
        }

        return null;
    }
};
