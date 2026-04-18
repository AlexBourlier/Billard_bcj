<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

final class ProjectionDecision
{
    private function __construct(
        public readonly string $mode,
        public readonly ?string $reason = null,
    ) {
    }

    public static function allow(): self
    {
        return self::real();
    }

    public static function real(): self
    {
        return new self(
            mode: 'real',
            reason: null,
        );
    }

    public static function preview(string $reason = 'dry_run'): self
    {
        return new self(
            mode: 'preview',
            reason: $reason,
        );
    }

    public static function skip(string $reason): self
    {
        return new self(
            mode: 'skip',
            reason: $reason,
        );
    }

    public function shouldProject(): bool
    {
        return $this->isReal() || $this->isPreview();
    }

    public function isReal(): bool
    {
        return $this->mode === 'real';
    }

    public function isPreview(): bool
    {
        return $this->mode === 'preview';
    }

    public function shouldSkip(): bool
    {
        return $this->mode === 'skip';
    }
}