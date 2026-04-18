<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Models\LicenseImportBatch;

final class TelematProjectionEligibilityDecider
{
    public function __construct(
        private readonly TelematProjectionDataInspector $inspector,
    ) {
    }

    public function decide(
        LicenseImportBatch $batch,
        LicenseImportExecutionContext $context,
        string $strategyName,
    ): ProjectionDecision {
        if (!$this->inspector->hasProjectableData($batch)) {
            return ProjectionDecision::skip('missing_projection_url');
        }

        if ($context->dryRun === true) {
            if ($strategyName === 'incremental') {
                return ProjectionDecision::preview('dry_run');
            }

            return ProjectionDecision::skip('dry_run');
        }

        if ($batch->is_active !== true) {
            return ProjectionDecision::skip('batch_not_activated');
        }

        return ProjectionDecision::real();
    }
}