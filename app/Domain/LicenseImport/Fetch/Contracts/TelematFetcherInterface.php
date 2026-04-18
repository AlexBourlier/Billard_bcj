<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Fetch\Contracts;

use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\TelematFetchConfig;
use App\Domain\LicenseImport\Fetch\TelematFetchResult;

interface TelematFetcherInterface
{
    /**
     * @throws TelematFetchException
     */
    public function fetch(TelematFetchConfig $config): TelematFetchResult;
}