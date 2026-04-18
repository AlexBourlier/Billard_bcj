<?php

declare(strict_types=1);

namespace Tests\Integration\Domain\LicenseImport\Fakes;

use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\TelematFetchResult;
use App\Domain\LicenseImport\Fetch\TelematFetchConfig;

final class FailingTelematFetcher implements TelematFetcherInterface
{
    public function __construct(
        private readonly TelematFetchException $exception,
    ) {
    }

    /**
     * @throws TelematFetchException
     */
    public function fetch(TelematFetchConfig $config): TelematFetchResult
    {
        throw $this->exception;
    }
}