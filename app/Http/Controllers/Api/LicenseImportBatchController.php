<?php

namespace App\Http\Controllers\Api;

use App\Domain\LicenseImport\Reporting\TelematBatchReportBuilder;
use App\Http\Controllers\Controller;
use App\Models\LicenseImportBatch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LicenseImportBatchController extends Controller
{
    //

    public function __construct(
        private readonly TelematBatchReportBuilder $reportBuilder,
    )
    {
        // Apply authentication middleware if needed
        // $this->middleware('auth:api');
        
    }

    public function index(Request $request): JsonResponse
    {
        $query = LicenseImportBatch::query()->latestFirst();

        if($request->filled('source')) {
            $query->where('source', $request->string('source')->toString());
        }

        if($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('is_active')){
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOL));
        }

        if ($request->filled('trigger_type')){
            $query->where('trigger_type', $request->string('trigger_type')->toString());
        }

        if ($request->filled('from')){
            $query->whereDate('started_at', '>=', $request->date('from'));
        }

        if ($request->filled('to')){
            $query->whereDate('started_at', '<=', $request->date('to'));
        }

        $batches = $query->paginate((int) $request->integer('per_page', 15));

        $data = $batches->getCollection()->map(function (LicenseImportBatch $batch): array {
            $summary = is_array($batch->summary) ? $batch->summary : [];

            return [
                'id' => $batch->getKey(),
                'source' => $batch->source,
                'status' => $batch->status?->value ?? $batch->status,
                'is_active' => (bool) $batch->is_active,
                'started_at' => $batch->started_at?->toIso8601String(),
                'finished_at' => $batch->finished_at?->toIso8601String(),
                'raw_rows_count' => $batch->raw_rows_count,
                'valid_rows_count' => $batch->valid_rows_count,
                'invalid_rows_count' => $batch->invalid_rows_count,
                'error_count' => $batch->error_count,
                'warning_count' => $batch->warning_count,
                'projection' => [
                    'executed' => (bool) ($summary['projection']['executed'] ?? false),
                    'strategy' => $summary['projection']['strategy'] ?? null,
                    'inserted_count' => (int) ($summary['projection']['inserted_count'] ?? 0),
                    'updated_count' => (int) ($summary['projection']['updated_count'] ?? 0),
                    'deleted_count' => (int) ($summary['projection']['deleted_count'] ?? 0),
                    'unchanged_count' => (int) ($summary['projection']['unchanged_count'] ?? 0),
                    'no_op' => (bool) ($summary['projection']['no_op'] ?? false),
                ],
            ];
        });

        return response()->json([
            'data' => $data,
            'meta' => [
                'current_page' => $batches->currentPage(),
                'last_page' => $batches->lastPage(),
                'per_page' => $batches->perPage(),
                'total' => $batches->total(),
            ],
        ]);
    }

    public function show(LicenseImportBatch $batch): JsonResponse
    {
        return response()->json([
            'data' => $this->reportBuilder->build($batch),
        ]);
    }

    public function report(LicenseImportBatch $batch): JsonResponse
    {
        return response()->json([
            'data' => $this->reportBuilder->build($batch),
        ]);
    }

    public function diff(LicenseImportBatch $batch): JsonResponse
    {
        $report = $this->reportBuilder->build($batch);

        return response()->json([
            'data' => [
                'batch_id' => $report['batch']['id'],
                'source' => $report['batch']['source'],
                'strategy' => $report['projection']['strategy'],
                'executed' => $report['projection']['executed'],
                'failed' => $report['projection']['failed'],
                'reason' => $report['projection']['reason'],
                'counts' => [
                    'inserted' => $report['projection']['inserted_count'],
                    'updated' => $report['projection']['updated_count'],
                    'deleted' => $report['projection']['deleted_count'],
                    'unchanged' => $report['projection']['unchanged_count'],
                    'no_op' => $report['projection']['no_op'],
                ],
                'diff' => $report['projection']['diff'],
            ],
        ]);
    }
}
