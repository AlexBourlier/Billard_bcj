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

final class TelematFullReplaceProjectionStrategy implements TelematProjectionStrategy
{
    public function __construct(
        private readonly TelematLicencieRowMapper $rowMapper,
    ) {
    }

    public function name(): string
    {
        return 'full_replace';
    }

    public function preview(LicenseImportBatch $batch): ProjectionResult
    {
        return ProjectionResult::skipped('preview_not_supported');
    }

    public function project(LicenseImportBatch $batch): ProjectionResult
    {
        if ($batch->status !== BatchStatus::Activated || $batch->is_active !== true) {
            throw TelematProjectionException::batchNotActivated($batch->getKey());
        }

        try {
            $rows = $this->buildProjectedRows($batch);
            $deletedCount = (int) DB::table('licencies')->count();

            DB::table('licencies')->delete();

            if (DB::getDriverName() === 'mysql') {
                DB::statement('ALTER TABLE licencies AUTO_INCREMENT = 1');
            }

            if (DB::getDriverName() === 'sqlite') {
                DB::statement("DELETE FROM sqlite_sequence WHERE name = 'licencies'");
            }

            if ($rows !== []) {
                DB::table('licencies')->insert($rows);
            }

            return $this->buildProjectedResult(
                sourceSnapshotCount: count($rows),
                deletedCount: $deletedCount,
                insertedCount: count($rows),
            );
        } catch (TelematProjectionException $exception) {
            throw $exception;
        } catch (QueryException $exception) {
            throw TelematProjectionException::databaseError($exception->getMessage(), $exception);
        }
    }

    /**
     * @return array<int, array{licence:string, nom:string, prenom:string, url:?string}>
     */
    private function buildProjectedRows(LicenseImportBatch $batch): array
    {
        /** @var Collection<int, LicenseImportSnapshot> $snapshots */
        $snapshots = LicenseImportSnapshot::query()
            ->where('import_batch_id', $batch->getKey())
            ->where('is_valid', true)
            ->orderBy('row_index')
            ->get();

        $rowsByLicence = [];

        foreach ($snapshots as $snapshot) {
            $row = $this->rowMapper->map($snapshot);

            if (!array_key_exists($row['licence'], $rowsByLicence)) {
                $rowsByLicence[$row['licence']] = $row;
            }
        }

        return array_values($rowsByLicence);
    }

    private function buildProjectedResult(
        int $sourceSnapshotCount,
        int $deletedCount,
        int $insertedCount,
    ): ProjectionResult {
        return new ProjectionResult(
            executed: true,
            sourceSnapshotCount: $sourceSnapshotCount,
            deletedCount: $deletedCount,
            insertedCount: $insertedCount,
            updatedCount: 0,
            unchangedCount: 0,
            noOp: false,
            failed: false,
            reason: null,
            context: [],
        );
    }
}