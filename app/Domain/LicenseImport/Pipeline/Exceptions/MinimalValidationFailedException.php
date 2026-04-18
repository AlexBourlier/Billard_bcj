<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Pipeline\Exceptions;

use RuntimeException;

final class MinimalValidationFailedException extends RuntimeException
{
}