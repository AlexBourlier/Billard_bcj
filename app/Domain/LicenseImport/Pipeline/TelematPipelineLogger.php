<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline;

use Illuminate\Support\Facades\Log;

final class TelematPipelineLogger
{
    /**
     * @param array<string, mixed> $context
     */
    public function info(string $message, array $context = []): void
    {
        $channel = config('license_import.observability.log_channel');

        if (is_string($channel) && trim($channel) !== '') {
            Log::channel($channel)->info($message, $context);
            return;
        }

        Log::info($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    public function error(string $message, array $context = []): void
    {
        $channel = config('license_import.observability.log_channel');

        if (is_string($channel) && trim($channel) !== '') {
            Log::channel($channel)->error($message, $context);
            return;
        }

        Log::error($message, $context);
    }

        /**
     * @param array<string, mixed> $context
     */
    private function logInfo(string $message, array $context = []): void
    {
        $channel = config('license_import.observability.log_channel');

        if (is_string($channel) && trim($channel) !== '') {
            Log::channel($channel)->info($message, $context);
            return;
        }

        Log::info($message, $context);
    }

    /**
     * @param array<string, mixed> $context
     */
    private function logError(string $message, array $context = []): void
    {
        $channel = config('license_import.observability.log_channel');

        if (is_string($channel) && trim($channel) !== '') {
            Log::channel($channel)->error($message, $context);
            return;
        }

        Log::error($message, $context);
    }
}