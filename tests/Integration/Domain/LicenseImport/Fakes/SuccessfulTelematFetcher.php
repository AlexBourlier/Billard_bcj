<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport\Fakes;

use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\TelematFetchConfig;
use App\Domain\LicenseImport\Fetch\TelematFetchResult;
use DateTimeImmutable;

final class SuccessfulTelematFetcher implements TelematFetcherInterface
{
    public function __construct(
        private readonly string $html,
    ) {
    }

    public function fetch(TelematFetchConfig $config): TelematFetchResult
    {
        return new TelematFetchResult(
            html: $this->html,
            httpStatus: 200,
            contentType: 'text/html',
            finalUrl: $config->url,
            fetchedAt: new DateTimeImmutable(),
        );
    }
}