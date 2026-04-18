<?php

namespace App\Domain\LicenseImport\Enums;

enum BatchStatus: string
{
    case Pending = 'pending';
    case Running = 'running';
    case Fetched = 'fetched';
    case Parsed = 'parsed';
    case ValidatedMinimal = 'validated_minimal';
    case ValidatedComparative = 'validated_comparative';
    case Activated = 'activated';
    case Rejected = 'rejected';
    case Failed = 'failed';
}