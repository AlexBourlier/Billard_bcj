<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LicenseImportSnapshot;
use App\Models\Licencies;
use Illuminate\Http\JsonResponse;

/**
 * Contrôleur API pour la gestion des licenciés.
 * 
 * @Group Licenciés
 *
 * Permet de :
 * - lister les licenciés
 * - rechercher par nom / prénom / numéro de licence
 * - récupérer les lots d’import valides
 *
 * Les réponses respectent le format standard :
 * data / meta / links / error
 */
class LicenciesController extends Controller
{
    /**
     * @group Licenciés
     * Retourne la liste complète des licenciés.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $licencies = Licencies::query()->get();

        return response()->json([
            'data' => $licencies,
            'meta' => [
                'count' => $licencies->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Recherche des licenciés par nom, prénom ou numéro de licence.
     *
     * @group Licenciés
     * La recherche est partielle (LIKE %value%).
     * Les résultats sont dédupliqués et limités aux champs utiles.
     *
     * @param string $name Terme de recherche
     *
     * @return JsonResponse
     */
    public function searchByName(string $name): JsonResponse
    {
        $licencies = Licencies::query()
            ->where(function ($query) use ($name) {
                $query->where('nom', 'like', '%' . $name . '%')
                    ->orWhere('prenom', 'like', '%' . $name . '%')
                    ->orWhere('licence', 'like', '%' . $name . '%');
            })
            ->select('prenom', 'nom', 'licence')
            ->distinct()
            ->get();

        return response()->json([
            'data' => $licencies,
            'meta' => [
                'search' => $name,
                'count' => $licencies->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }

    /**
     * Retourne la liste des identifiants de batch d’import valides.
     *
     * @group Licenciés
     * Permet d’identifier les imports de licences exploitables.
     *
     * @return JsonResponse
     */
    public function batches(): JsonResponse
    {
        $batches = LicenseImportSnapshot::query()
            ->select('import_batch_id')
            ->where('is_valid', true)
            ->distinct()
            ->get()
            ->pluck('import_batch_id');

        return response()->json([
            'data' => $batches,
            'meta' => [
                'count' => $batches->count(),
            ],
            'links' => [],
            'error' => null,
        ]);
    }
}