<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection\Strategies;

use App\Domain\LicenseImport\Enums\BatchStatus;
use App\Domain\LicenseImport\Projection\Contracts\TelematProjectionStrategy;
use App\Domain\LicenseImport\Projection\Exceptions\TelematProjectionException;
use App\Domain\LicenseImport\Projection\ProjectionResult;
use App\Domain\LicenseImport\Projection\TelematLicencieRowMapper;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class TelematIncrementalProjectionStrategy implements TelematProjectionStrategy
{
    public function __construct(
        private readonly TelematLicencieRowMapper $rowMapper,
    ) {
    }

    public function name(): string
    {
        return 'incremental';
    }

    public function preview(LicenseImportBatch $batch): ProjectionResult
    {
        try {
            $diff = $this->computeDiff($batch);

            return $this->buildPreviewResult($diff);
        } catch (TelematProjectionException $exception) {
            throw $exception;
        } catch (QueryException $exception) {
            throw TelematProjectionException::databaseError($exception->getMessage(), $exception);
        }
    }

    public function project(LicenseImportBatch $batch): ProjectionResult
    {
        if ($batch->status !== BatchStatus::Activated || $batch->is_active !== true) {
            throw TelematProjectionException::batchNotActivated($batch->getKey());
        }

        try {
            return DB::transaction(function () use ($batch): ProjectionResult {
                $diff = $this->computeDiff($batch);

                $rowsToInsert = $diff['rows_to_insert'];
                $rowsToUpdate = $diff['rows_to_update'];
                $licencesToDelete = $diff['licences_to_delete'];

                if ($rowsToInsert !== []) {
                    DB::table('licencies')->insert($rowsToInsert);
                }

                if ($rowsToUpdate !== []) {
                    DB::table('licencies')->upsert(
                        $rowsToUpdate,
                        ['licence'],
                        ['nom', 'prenom', 'url'],
                    );
                }

                if ($licencesToDelete !== []) {
                    DB::table('licencies')
                        ->whereIn('licence', $licencesToDelete)
                        ->delete();
                }

                return $this->buildProjectedResult($diff);
            });
        } catch (TelematProjectionException $exception) {
            throw $exception;
        } catch (QueryException $exception) {
            throw TelematProjectionException::databaseError($exception->getMessage(), $exception);
        }
    }

    /**
     * @return array{
     *     source_snapshot_count:int,
     *     rows_to_insert:array<int, array{licence:string, nom:string, prenom:string, url:string}>,
     *     rows_to_update:array<int, array{licence:string, nom:string, prenom:string, url:string}>,
     *     licences_to_delete:array<int, string>,
     *     unchanged_count:int,
     *     diff: array{
     *         inserted: array<int, array<string, mixed>>,
     *         updated: array<int, array<string, mixed>>,
     *         deleted: array<int, array<string, mixed>>
     *     }
     * }
     */
    private function computeDiff(LicenseImportBatch $batch): array
    {
        $snapshots = LicenseImportSnapshot::query()
            ->where('import_batch_id', $batch->getKey())
            ->where('is_valid', true)
            ->orderBy('row_index')
            ->get();

        $projectedRows = $this->buildProjectedRows($snapshots);
        $projectedLicences = array_keys($projectedRows);

        $existingRows = $this->loadExistingRowsForProjectedLicences($projectedLicences);
        $allExistingLicences = DB::table('licencies')
            ->pluck('licence')
            ->all();

        $rowsToInsert = [];
        $rowsToUpdate = [];
        $unchangedCount = 0;
        $insertedDiff = [];
        $updatedDiff = [];
        $deletedDiff = [];

        foreach ($projectedRows as $licence => $row) {
            $existing = $existingRows->get($licence);

            if ($existing === null) {
                $rowsToInsert[] = $row;
                $insertedDiff[] = [
                    'licence' => $licence,
                    'new' => $row,
                ];
                continue;
            }

            $currentRow = [
                'licence' => $existing->licence,
                'nom' => $existing->nom,
                'prenom' => $existing->prenom,
                'url' => $existing->url,
            ];

            if ($currentRow !== $row) {
                $rowsToUpdate[] = $row;
                $updatedDiff[] = [
                    'licence' => $licence,
                    'old' => $currentRow,
                    'new' => $row,
                ];
                continue;
            }

            $unchangedCount++;
        }

        $licencesToDelete = array_values(array_diff($allExistingLicences, $projectedLicences));

        foreach ($licencesToDelete as $licenceToDelete) {
            $deletedDiff[] = [
                'licence' => $licenceToDelete,
            ];
        }

        return [
            'source_snapshot_count' => $snapshots->count(),
            'rows_to_insert' => $rowsToInsert,
            'rows_to_update' => $rowsToUpdate,
            'licences_to_delete' => $licencesToDelete,
            'unchanged_count' => $unchangedCount,
            'diff' => [
                'inserted' => $insertedDiff,
                'updated' => $updatedDiff,
                'deleted' => $deletedDiff,
            ],
        ];
    }

    /**
     * @param Collection<int, LicenseImportSnapshot> $snapshots
     * @return array<string, array{licence:string, nom:string, prenom:string, url:string}>
     */
    private function buildProjectedRows(Collection $snapshots): array
    {
        $projectedRows = [];

        foreach ($snapshots as $snapshot) {
            $row = $this->rowMapper->map($snapshot);
            $projectedRows[$row['licence']] = $row;
        }

        return $projectedRows;
    }

    /**
     * @param array<int, string> $projectedLicences
     * @return Collection<string, object>
     */
    private function loadExistingRowsForProjectedLicences(array $projectedLicences): Collection
    {
        if ($projectedLicences === []) {
            return collect();
        }

        return DB::table('licencies')
            ->whereIn('licence', $projectedLicences)
            ->get(['licence', 'nom', 'prenom', 'url'])
            ->keyBy('licence');
    }

    /**
     * @param array{
     *     source_snapshot_count:int,
     *     rows_to_insert:array<int, array{licence:string, nom:string, prenom:string, url:string}>,
     *     rows_to_update:array<int, array{licence:string, nom:string, prenom:string, url:string}>,
     *     licences_to_delete:array<int, string>,
     *     unchanged_count:int,
     *     diff: array{
     *         inserted: array<int, array<string, mixed>>,
     *         updated: array<int, array<string, mixed>>,
     *         deleted: array<int, array<string, mixed>>
     *     }
     * } $diff
     */
    private function buildPreviewResult(array $diff): ProjectionResult
    {
        $insertedCount = count($diff['rows_to_insert']);
        $updatedCount = count($diff['rows_to_update']);
        $deletedCount = count($diff['licences_to_delete']);
        $unchangedCount = $diff['unchanged_count'];

        return new ProjectionResult(
            executed: false,
            sourceSnapshotCount: $diff['source_snapshot_count'],
            deletedCount: $deletedCount,
            insertedCount: $insertedCount,
            updatedCount: $updatedCount,
            unchangedCount: $unchangedCount,
            noOp: $this->isNoOp($insertedCount, $updatedCount, $deletedCount),
            failed: false,
            reason: 'dry_run',
            context: [
                'preview' => [
                    'inserted_count' => $insertedCount,
                    'updated_count' => $updatedCount,
                    'deleted_count' => $deletedCount,
                    'unchanged_count' => $unchangedCount,
                ],
            ],
            diff: $diff['diff'],
        );
    }

    /**
     * @param array{
     *     source_snapshot_count:int,
     *     rows_to_insert:array<int, array{licence:string, nom:string, prenom:string, url:string}>,
     *     rows_to_update:array<int, array{licence:string, nom:string, prenom:string, url:string}>,
     *     licences_to_delete:array<int, string>,
     *     unchanged_count:int,
     *     diff: array{
     *         inserted: array<int, array<string, mixed>>,
     *         updated: array<int, array<string, mixed>>,
     *         deleted: array<int, array<string, mixed>>
     *     }
     * } $diff
     */
    private function buildProjectedResult(array $diff): ProjectionResult
    {
        $insertedCount = count($diff['rows_to_insert']);
        $updatedCount = count($diff['rows_to_update']);
        $deletedCount = count($diff['licences_to_delete']);
        $unchangedCount = $diff['unchanged_count'];

        return new ProjectionResult(
            executed: true,
            sourceSnapshotCount: $diff['source_snapshot_count'],
            deletedCount: $deletedCount,
            insertedCount: $insertedCount,
            updatedCount: $updatedCount,
            unchangedCount: $unchangedCount,
            noOp: $this->isNoOp($insertedCount, $updatedCount, $deletedCount),
            failed: false,
            reason: null,
            context: [],
            diff: $diff['diff'],
        );
    }

    private function isNoOp(int $insertedCount, int $updatedCount, int $deletedCount): bool
    {
        return $insertedCount === 0
            && $updatedCount === 0
            && $deletedCount === 0;
    }
}