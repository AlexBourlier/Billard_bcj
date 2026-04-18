<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection\Exceptions;

use RuntimeException;
use Throwable;

final class TelematProjectionException extends RuntimeException
{
    public static function batchNotActivated(int|string|null $batchId): self
    {
        return new self(sprintf(
            'Cannot project batch [%s] because it is not activated.',
            (string) $batchId,
        ));
    }

    public static function missingUrl(int|string|null $snapshotId): self
    {
        return new self(sprintf(
            'Missing projection url for snapshot #%s.',
            (string) $snapshotId,
        ));
    }

    public static function invalidExtraData(int|string|null $snapshotId): self
    {
        return new self(sprintf(
            'Invalid extra_data payload for snapshot #%s.',
            (string) $snapshotId,
        ));
    }

    public static function missingRequiredField(string $field, int|string|null $snapshotId): self
    {
        return new self(sprintf(
            'Missing required projection field [%s] for snapshot #%s.',
            $field,
            (string) $snapshotId,
        ));
    }

    public static function databaseError(string $message, ?Throwable $previous = null): self
    {
        return new self(
            sprintf('Projection database error: %s', $message),
            previous: $previous,
        );
    }
}