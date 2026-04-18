<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

use App\Domain\LicenseImport\Projection\Contracts\TelematProjectionStrategy;
use App\Domain\LicenseImport\Projection\Strategies\TelematFullReplaceProjectionStrategy;
use App\Domain\LicenseImport\Projection\Strategies\TelematIncrementalProjectionStrategy;
use App\Domain\LicenseImport\Projection\Strategies\TelematNoopProjectionStrategy;


final class TelematProjectionStrategyResolver
{
    public function resolve(): TelematProjectionStrategy
    {
        $strategy = config('license_import.projection.strategy');

        return match ($strategy) {
            'full_replace' => app(TelematFullReplaceProjectionStrategy::class),
            'noop' => app(TelematNoopProjectionStrategy::class),
            'incremental' => app(TelematIncrementalProjectionStrategy::class),
            default => throw new \LogicException(
                sprintf('Unknown projection strategy [%s].', $strategy)
            ),
        };
    }
}