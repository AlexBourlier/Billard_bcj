<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection\Contracts;

use App\Domain\LicenseImport\Projection\ProjectionResult;
use App\Models\LicenseImportBatch;

interface TelematProjectionStrategy
{
    public function name(): string;

    public function project(LicenseImportBatch $batch): ProjectionResult;

    public function preview(LicenseImportBatch $batch): ProjectionResult;
}