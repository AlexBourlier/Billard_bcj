<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\TelematHttpFetcher;
use Illuminate\Support\ServiceProvider;

final class LicenseImportServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TelematFetcherInterface::class, TelematHttpFetcher::class);
    }
}