<?php

namespace App\Services;

use App\Models\CalendarEvent;
use App\Models\CueScoreRankingFetch;
use App\Models\Document;
use App\Models\Licencies;
use App\Models\LicenseImportBatch;
use App\Models\Partenaire;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

/**
 * Agrege les indicateurs du tableau de bord d'administration.
 *
 * Regle stricte : uniquement des donnees reellement presentes en base. Les
 * indicateurs non disponibles (repartition des licencies, brouillons, cotisations)
 * ne sont pas approximes ici : la vue les signale comme « non encore suivis ».
 *
 * Toutes les requetes sont agregees ou bornees (pas de chargement complet en
 * memoire, pas de N+1). Le service pourra ulterieurement filtrer par saison
 * lorsque les entites concernees porteront cette information.
 */
class ClubDashboardService
{
    private const DISCIPLINES = [
        1 => 'Blackball',
        2 => 'Carambole',
        3 => 'Snooker',
        4 => 'Américain',
    ];

    private const EXPIRY_WINDOW_DAYS = 30;

    /**
     * @return array<string, mixed>
     */
    public function metrics(): array
    {
        return [
            'articles' => $this->articles(),
            'documents' => $this->documents(),
            'partenaires' => $this->partenaires(),
            'licencies' => $this->licencies(),
            'evenements' => $this->evenements(),
            'technique' => $this->technique(),
        ];
    }

    private function articles(): array
    {
        $byDiscipline = [];
        $rows = Post::query()
            ->select('discipline', DB::raw('count(*) as total'))
            ->groupBy('discipline')
            ->pluck('total', 'discipline');

        foreach ($rows as $code => $total) {
            $name = self::DISCIPLINES[(int) $code] ?? 'Le Club';
            $byDiscipline[$name] = ($byDiscipline[$name] ?? 0) + (int) $total;
        }
        arsort($byDiscipline);

        return [
            'total' => Post::count(),
            'latest' => Post::query()->orderByDesc('created_at')->first(['id', 'title', 'created_at']),
            'recentlyUpdated' => Post::query()
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get(['id', 'title', 'updated_at']),
            'byDiscipline' => $byDiscipline,
        ];
    }

    private function documents(): array
    {
        return [
            'total' => Document::count(),
        ];
    }

    private function partenaires(): array
    {
        return [
            'active' => Partenaire::visible()->count(),
            'expiringSoon' => Partenaire::query()
                ->whereNotNull('date_fin')
                ->whereDate('date_fin', '>=', now()->toDateString())
                ->whereDate('date_fin', '<=', now()->addDays(self::EXPIRY_WINDOW_DAYS)->toDateString())
                ->orderBy('date_fin')
                ->get(['id', 'titre', 'date_fin']),
        ];
    }

    private function licencies(): array
    {
        // Seul le total est disponible : la table licencies ne porte ni
        // discipline, ni categorie, ni statut. Les repartitions sont donc
        // signalees « non suivies » cote vue.
        return [
            'total' => Licencies::count(),
        ];
    }

    private function evenements(): array
    {
        $today = now()->toDateString();

        return [
            'upcomingCount' => CalendarEvent::query()->whereDate('date_debut', '>=', $today)->count(),
            'upcoming' => CalendarEvent::query()
                ->whereDate('date_debut', '>=', $today)
                ->orderBy('date_debut')
                ->limit(5)
                ->get(['id', 'titre', 'date_debut', 'lieu']),
        ];
    }

    private function technique(): array
    {
        return [
            'lastImport' => LicenseImportBatch::query()->orderByDesc('started_at')->first(),
            'failedImports' => LicenseImportBatch::query()->where('status', 'failed')->count(),
            'cuescoreErrors' => CueScoreRankingFetch::query()->where('status', 'failed')->count(),
        ];
    }
}
