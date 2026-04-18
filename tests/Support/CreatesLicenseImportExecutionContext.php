<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\TriggerType;
use Carbon\CarbonImmutable;

trait CreatesLicenseImportExecutionContext
{
    protected function makeLicenseImportExecutionContext(
        string $source = 'telemat',
        TriggerType $triggerType = TriggerType::Manual,
        ?int $triggeredByUserId = null,
        ?string $triggeredByLabel = null,
        ?CarbonImmutable $requestedAt = null,
        bool $dryRun = false,
    ): LicenseImportExecutionContext {
        return new LicenseImportExecutionContext(
            source: $source,
            triggerType: $triggerType,
            triggeredByUserId: $triggeredByUserId,
            triggeredByLabel: $triggeredByLabel,
            requestedAt: $requestedAt ?? CarbonImmutable::parse('2026-04-15T10:00:00+02:00'),
            dryRun: $dryRun,
        );
    }
}