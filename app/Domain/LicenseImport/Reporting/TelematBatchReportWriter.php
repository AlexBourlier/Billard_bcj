<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Reporting;

use App\Models\LicenseImportBatch;
use Illuminate\Support\Facades\File;
use JsonException;

final class TelematBatchReportWriter
{
    public function __construct(
        private readonly TelematBatchReportBuilder $builder,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function write(LicenseImportBatch $batch): string
    {
        $directory = storage_path('app/license-import/reports');

        if (!File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . sprintf(
            '%s-batch-%d.json',
            $batch->source,
            $batch->getKey(),
        );

        $payload = json_encode(
            $this->builder->build($batch),
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR,
        );

        File::put($path, $payload);

        return $path;
    }
}