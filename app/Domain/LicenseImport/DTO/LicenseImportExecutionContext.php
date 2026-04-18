<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\DTO;

use App\Domain\LicenseImport\Enums\TriggerType;
use Carbon\CarbonImmutable;

final class LicenseImportExecutionContext
{
    public function __construct(
        public readonly string $source,
        public readonly TriggerType $triggerType,
        public readonly ?int $triggeredByUserId,
        public readonly ?string $triggeredByLabel,
        public readonly CarbonImmutable $requestedAt,
        public readonly bool $dryRun,
    ) {
    }

    public static function fromArray(array $data): static
    {
        return new static(
            source: $data['source'],
            triggerType: TriggerType::from($data['trigger_type']),
            triggeredByUserId: isset($data['triggered_by_user_id']) ? (int) $data['triggered_by_user_id'] : null,
            triggeredByLabel: $data['triggered_by_label'] ?? null,
            requestedAt: CarbonImmutable::parse($data['requested_at']),
            dryRun: (bool) ($data['dry_run'] ?? false),
        );
    }

    public function toArray(): array
    {
        return [
            'source' => $this->source,
            'trigger_type' => $this->triggerType->value,
            'triggered_by_user_id' => $this->triggeredByUserId,
            'triggered_by_label' => $this->triggeredByLabel,
            'requested_at' => $this->requestedAt->toIso8601String(),
            'dry_run' => $this->dryRun,
        ];
    }
}