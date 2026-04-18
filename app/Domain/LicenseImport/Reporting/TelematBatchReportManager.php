<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Reporting;

use App\Domain\LicenseImport\Pipeline\TelematPipelineLogger;
use App\Models\LicenseImportBatch;
use Throwable;

final class TelematBatchReportManager
{
    public function __construct(
        private readonly TelematBatchReportWriter $writer,
        private readonly TelematPipelineLogger $logger,
    ) {
    }

    public function writeSafely(LicenseImportBatch $batch): void
    {
        try {
            $path = $this->writer->write($batch->fresh());

            $meta = is_array($batch->meta) ? $batch->meta : [];

            $batch->forceFill([
                'meta' => array_replace_recursive($meta, [
                    'report' => [
                        'path' => $path,
                        'written' => true,
                        'written_at' => now()->toIso8601String(),
                    ],
                ]),
            ])->save();
        } catch (Throwable $exception) {
            $meta = is_array($batch->meta) ? $batch->meta : [];

            $batch->forceFill([
                'meta' => array_replace_recursive($meta, [
                    'report' => [
                        'written' => false,
                        'error' => $exception->getMessage(),
                    ],
                ]),
            ])->save();

            $this->logger->error('license_import.reporting.write_failed', [
                'batch_id' => $batch->getKey(),
                'source' => $batch->source,
                'message' => $exception->getMessage(),
                'exception_class' => $exception::class,
            ]);
        }
    }
}