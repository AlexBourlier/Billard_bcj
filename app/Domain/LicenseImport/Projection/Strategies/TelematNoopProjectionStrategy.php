<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection\Strategies;

use App\Domain\LicenseImport\Projection\Contracts\TelematProjectionStrategy;
use App\Domain\LicenseImport\Projection\ProjectionResult;
use App\Models\LicenseImportBatch;

final class TelematNoopProjectionStrategy implements TelematProjectionStrategy
{
    public function name(): string
    {
        return 'noop';
    }

    public function project(LicenseImportBatch $batch): ProjectionResult
    {
        return ProjectionResult::skipped('noop_strategy');
    }

    public function preview(LicenseImportBatch $batch): ProjectionResult
    {
        return ProjectionResult::skipped('noop_strategy');
    }
}