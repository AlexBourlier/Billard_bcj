<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Models\LicenseImportBatch;

final class TelematLicenseProjector
{
    public function __construct(
        private readonly TelematProjectionStrategyResolver $resolver,
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    public function project(LicenseImportBatch $batch): ProjectionResult
    {
        $strategy = $this->resolver->resolve();
        $result = $strategy->project($batch);

        $this->logResult('completed', $batch, $strategy->name(), $result);

        return $result;
    }

    public function preview(LicenseImportBatch $batch): ProjectionResult
    {
        $strategy = $this->resolver->resolve();
        $result = $strategy->preview($batch);

        $this->logResult('preview_completed', $batch, $strategy->name(), $result);

        return $result;
    }

    public function strategyName(): string
    {
        return $this->resolver->resolve()->name();
    }

    private function logResult(
        string $suffix,
        LicenseImportBatch $batch,
        string $strategyName,
        ProjectionResult $result,
    ): void {
        $diff = is_array($result->diff) ? $result->diff : [];

        $insertedDiff = is_array($diff['inserted'] ?? null) ? $diff['inserted'] : [];
        $updatedDiff = is_array($diff['updated'] ?? null) ? $diff['updated'] : [];
        $deletedDiff = is_array($diff['deleted'] ?? null) ? $diff['deleted'] : [];

        $this->logger->info("license_import.pipeline.projection.{$suffix}", [
            'batch_id' => $batch->getKey(),
            'strategy' => $strategyName,
            'executed' => $result->executed,
            'failed' => $result->failed,
            'reason' => $result->reason,
            'source_snapshot_count' => $result->sourceSnapshotCount,
            'deleted_count' => $result->deletedCount,
            'inserted_count' => $result->insertedCount,
            'updated_count' => $result->updatedCount,
            'unchanged_count' => $result->unchangedCount,
            'no_op' => $result->noOp,
            'diff_inserted_count' => count($insertedDiff),
            'diff_updated_count' => count($updatedDiff),
            'diff_deleted_count' => count($deletedDiff),
            'context' => $result->context,
        ]);
    }
}