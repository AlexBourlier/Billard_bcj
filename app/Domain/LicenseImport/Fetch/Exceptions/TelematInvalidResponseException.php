<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Fetch\Exceptions;

final class TelematInvalidResponseException extends TelematFetchException
{
    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        string $message,
        public readonly string $reason,
        public readonly array $context = [],
        int $code = 0,
        ?\Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}