<?php

namespace App\Services\CueScore;

class CueScoreNameNormalizer
{
    public function normalize(?string $value): string
    {
        if ($value === null) {
            return '';
        }

        $value = trim($value);
        $value = mb_strtolower($value, 'UTF-8');

        $replacements = [
            'à' => 'a', 'á' => 'a', 'â' => 'a', 'ä' => 'a',
            'ç' => 'c',
            'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
            'ñ' => 'n',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'ö' => 'o',
            'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u',
            'ý' => 'y', 'ÿ' => 'y',
            '\'' => ' ',
            '-' => ' ',
        ];

        $value = strtr($value, $replacements);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return trim($value);
    }

    public function normalizeFullName(?string $firstname, ?string $lastname): string
    {
        return $this->normalize(trim(($firstname ?? '') . ' ' . ($lastname ?? '')));
    }
}