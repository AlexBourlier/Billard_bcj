<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

use App\Models\LicenseImportBatch;
use App\Models\LicenseImportSnapshot;

final class TelematProjectionDataInspector
{
    public function hasProjectableData(LicenseImportBatch $batch): bool
    {
        return LicenseImportSnapshot::query()
            ->where('import_batch_id', $batch->getKey())
            ->where('is_valid', true)
            ->exists();
    }
}