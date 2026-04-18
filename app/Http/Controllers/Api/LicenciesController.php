<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LicenseImportSnapshot;
use App\Models\Licencies;
use Illuminate\Http\Request;

class LicenciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licencies = Licencies::all();
        $count = $licencies->count();
        return response()->json(['count' => $count, 'data' => $licencies]);
    }


    public function searchByName($name)
    {
        $licencies = Licencies::where(function ($query) use ($name) {
                $query->where('nom', 'like', '%' . $name . '%')
                    ->orWhere('prenom', 'like', '%' . $name . '%')
                    ->orWhere('licence', 'like', '%' . $name . '%');
            })
            ->select('prenom', 'nom', 'licence')
            ->distinct()
            ->get();

        $count = $licencies->count();
        return response()->json(['count' => $count, 'data' => $licencies]);
    }

    /**
     * Display batches of license imports.
     */
    public function batches()
    {
        $batches = LicenseImportSnapshot::query()
            ->select('import_batch_id')
            ->where('is_valid', true)
            ->distinct()
            ->get()
            ->pluck('import_batch_id');
        return response()->json(['count' => $batches->count(), 'data' => $batches]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
