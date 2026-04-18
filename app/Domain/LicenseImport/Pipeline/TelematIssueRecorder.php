<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline;

use App\Domain\LicenseImport\DTO\LicenseImportExecutionContext;
use App\Domain\LicenseImport\Enums\IssueSeverity;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException;
use App\Models\LicenseImportBatch;
use App\Models\LicenseImportIssue;
use Throwable;

final class TelematIssueRecorder
{
    /**
     * @param array<string, mixed> $context
     */
    public function record(
        LicenseImportBatch $batch,
        IssueSeverity $severity,
        string $code,
        string $message,
        array $context = [],
        ?int $rowIndex = null,
    ): LicenseImportIssue {
        /** @var LicenseImportIssue $issue */
        $issue = $batch->issues()->create([
            'severity' => $severity,
            'code' => $code,
            'message' => $message,
            'context' => $context,
            'row_index' => $rowIndex,
        ]);

        return $issue;
    }

    /**
     * @return array<string, mixed>
     */
    public function makeContext(
        LicenseImportExecutionContext $context,
        Throwable $exception,
    ): array {
        return [
            'exception_class' => $exception::class,
            'source' => $context->source,
            'trigger_type' => $context->triggerType->value,
            'triggered_by_user_id' => $context->triggeredByUserId,
            'triggered_by' => $context->triggeredByLabel,
            'dry_run' => $context->dryRun,
        ];
    }

    public function resolveFetchIssueCode(TelematFetchException $exception): string
    {
        return match (true) {
            $exception instanceof TelematNetworkException => 'fetch_network_failure',
            $exception instanceof TelematInvalidResponseException => 'fetch_invalid_response',
            default => 'fetch_failed',
        };
    }
}