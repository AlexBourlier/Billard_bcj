<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Normalization;

use Normalizer;

final class TelematHeaderNormalizer
{
    public function normalize(string $input): string
    {
        $config = config('license_import.parsing.header_normalization', []);

        if (!is_array($config)) {
            $config = [];
        }

        if (($config['trim'] ?? true) === true) {
            $input = trim($input);
        }

        if (($config['strip_accents'] ?? true) === true) {
            $input = $this->stripAccents($input);
        }

        if (($config['lowercase'] ?? true) === true) {
            $input = mb_strtolower($input);
        }

        if (($config['strip_punctuation'] ?? true) === true) {
            $input = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $input) ?? $input;
        }

        if (($config['collapse_spaces'] ?? true) === true) {
            $input = preg_replace('/\s+/u', ' ', $input) ?? $input;
            $input = trim($input);
        }

        return $input;
    }

    private function stripAccents(string $input): string
    {
        if (!class_exists(Normalizer::class)) {
            return $input;
        }

        $normalized = Normalizer::normalize($input, Normalizer::FORM_D);

        if ($normalized === false) {
            return $input;
        }

        return preg_replace('/\p{Mn}+/u', '', $normalized) ?? $input;
    }
}