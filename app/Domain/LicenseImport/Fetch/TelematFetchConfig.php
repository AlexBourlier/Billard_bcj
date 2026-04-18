<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Fetch;

use InvalidArgumentException;

final class TelematFetchConfig
{
    public function __construct(
        public readonly string $url,
        public readonly string $method,
        public readonly array $formFields,
        public readonly array $headers,
        public readonly ?string $authUsername,
        public readonly ?string $authPassword,
        public readonly int $timeoutSeconds,
        public readonly int $connectTimeoutSeconds,
        public readonly int $retryTimes,
        public readonly int $retrySleepMilliseconds,
        public readonly bool $followRedirects,
        public readonly array $extraOptions,
        public readonly array $expectedContentTypes,
        public readonly int $minBodyLength,
        public readonly array $errorMarkers,
        public readonly array $requiredMarkers,
    ) {
        if (trim($this->url) === '') {
            throw new InvalidArgumentException('Telemat fetch config url must not be empty.');
        }

        if (trim($this->method) === '') {
            throw new InvalidArgumentException('Telemat fetch config method must not be empty.');
        }

        if ($this->timeoutSeconds <= 0) {
            throw new InvalidArgumentException('Telemat fetch config timeoutSeconds must be greater than 0.');
        }

        if ($this->connectTimeoutSeconds <= 0) {
            throw new InvalidArgumentException('Telemat fetch config connectTimeoutSeconds must be greater than 0.');
        }

        if ($this->retryTimes < 0) {
            throw new InvalidArgumentException('Telemat fetch config retryTimes must be greater than or equal to 0.');
        }

        if ($this->retrySleepMilliseconds < 0) {
            throw new InvalidArgumentException('Telemat fetch config retrySleepMilliseconds must be greater than or equal to 0.');
        }

        if ($this->minBodyLength < 0) {
            throw new InvalidArgumentException('Telemat fetch config minBodyLength must be greater than or equal to 0.');
        }
    }
}