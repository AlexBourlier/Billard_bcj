<?php

namespace App\Domain\LicenseImport\Enums;

enum TriggerType: string
{
    case Manual = 'manual';
    case Scheduled = 'scheduled';
    case Test = 'test';
    case Retry = 'retry';
}