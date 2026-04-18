<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

final class ProjectionResult
{
    /**
     * @param array<string, mixed> $context
     * @param array{
     *     inserted?: array<int, array<string, mixed>>,
     *     updated?: array<int, array<string, mixed>>,
     *     deleted?: array<int, array<string, mixed>>
     * } $diff
     */
    public function __construct(
        public readonly bool $executed,
        public readonly int $sourceSnapshotCount,
        public readonly int $deletedCount,
        public readonly int $insertedCount,
        public readonly int $updatedCount,
        public readonly int $unchangedCount,
        public readonly bool $noOp,
        public readonly bool $failed = false,
        public readonly ?string $reason = null,
        public readonly array $context = [],
        public readonly array $diff = [],
    ) {
    }

    public static function skipped(string $reason): self
    {
        return new self(
            executed: false,
            sourceSnapshotCount: 0,
            deletedCount: 0,
            insertedCount: 0,
            updatedCount: 0,
            unchangedCount: 0,
            noOp: false,
            failed: false,
            reason: $reason,
            context: [],
            diff: [],
        );
    }

    /**
     * @param array<string, mixed> $context
     */
    public static function failed(string $reason, array $context = []): self
    {
        return new self(
            executed: false,
            sourceSnapshotCount: 0,
            deletedCount: 0,
            insertedCount: 0,
            updatedCount: 0,
            unchangedCount: 0,
            noOp: false,
            failed: true,
            reason: $reason,
            context: $context,
            diff: [],
        );
    }
}