<?php

namespace App\Domain\LicenseImport\Enums;

enum JobOutcome: string
{
    case SkippedLocked = 'skipped_locked';
    case FailedTechnical = 'failed_technical';
    case RejectedValidation = 'rejected_validation';
    case SucceededActivated = 'succeeded_activated';
}