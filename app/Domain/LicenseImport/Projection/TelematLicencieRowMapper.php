<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Projection;

use App\Domain\LicenseImport\Projection\Exceptions\TelematProjectionException;
use App\Models\LicenseImportSnapshot;

final class TelematLicencieRowMapper
{
    /**
     * @return array{
     *     licence: string,
     *     nom: string,
     *     prenom: string,
     *     url: ?string
     * }
     */
    public function map(LicenseImportSnapshot $snapshot): array
    {
        $licenseNumber = $this->requireNonEmptyString(
            $snapshot->license_number,
            TelematProjectionException::missingRequiredField(
                field: 'license_number',
                snapshotId: $snapshot->getKey(),
            ),
        );

        $lastName = $this->requireNonEmptyString(
            $snapshot->last_name,
            TelematProjectionException::missingRequiredField(
                field: 'last_name',
                snapshotId: $snapshot->getKey(),
            ),
        );

        $firstName = $this->requireNonEmptyString(
            $snapshot->first_name,
            TelematProjectionException::missingRequiredField(
                field: 'first_name',
                snapshotId: $snapshot->getKey(),
            ),
        );

        return [
            'licence' => $licenseNumber,
            'nom' => $lastName,
            'prenom' => $firstName,
            'url' => $this->extractOptionalUrl($snapshot),
        ];
    }

    private function extractOptionalUrl(LicenseImportSnapshot $snapshot): ?string
    {
        $extraData = $snapshot->extra_data;

        if (!is_array($extraData)) {
            return null;
        }

        $normalizedSourceRow = is_array($extraData['normalized_source_row'] ?? null)
            ? $extraData['normalized_source_row']
            : [];

        $sourceRow = is_array($extraData['source_row'] ?? null)
            ? $extraData['source_row']
            : [];

        $candidates = [
            $normalizedSourceRow['url'] ?? null,
            $normalizedSourceRow['URL'] ?? null,
            $sourceRow['url'] ?? null,
            $sourceRow['URL'] ?? null,
        ];

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && trim($candidate) !== '') {
                return trim($candidate);
            }
        }

        return null;
    }

    private function requireNonEmptyString(
        mixed $value,
        TelematProjectionException $exception,
    ): string {
        if (!is_string($value) || trim($value) === '') {
            throw $exception;
        }

        return trim($value);
    }
}