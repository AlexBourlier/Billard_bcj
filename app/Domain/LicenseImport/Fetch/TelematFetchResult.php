<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Fetch;

use DateTimeImmutable;
use InvalidArgumentException;

final class TelematFetchResult
{
    public function __construct(
        public readonly string $html,
        public readonly int $httpStatus,
        public readonly ?string $contentType,
        public readonly ?string $finalUrl,
        public readonly DateTimeImmutable $fetchedAt,
    ) {
        if ($this->httpStatus < 100 || $this->httpStatus > 599) {
            throw new InvalidArgumentException('Telemat fetch result httpStatus must be between 100 and 599.');
        }
    }
}