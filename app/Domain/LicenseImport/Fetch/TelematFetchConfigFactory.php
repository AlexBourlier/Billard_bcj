<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Fetch;

use InvalidArgumentException;

final class TelematFetchConfigFactory
{
    public function make(): TelematFetchConfig
    {
        $root = config('license_import');

        if (!is_array($root)) {
            throw new InvalidArgumentException('The license_import config root must be an array.');
        }

        $source = $this->requireArray($root, 'source', 'license_import.source');
        $http = $this->requireArray($root, 'http', 'license_import.http');

        $form = $this->arrayOrDefault($source, 'form', [], 'license_import.source.form');
        $formFields = $this->arrayOrDefault($form, 'fields', [], 'license_import.source.form.fields');

        $auth = $this->arrayOrDefault($source, 'auth', [], 'license_import.source.auth');
        $retries = $this->arrayOrDefault($http, 'retries', [], 'license_import.http.retries');

        $headers = $this->arrayOrDefault($http, 'headers', [], 'license_import.http.headers');
        $headers = $this->enrichHeadersWithUserAgent($headers, $http);

        return new TelematFetchConfig(
            url: $this->buildSourceUrl($source),
            method: strtoupper($this->stringOrDefault($source, 'method', 'POST', 'license_import.source.method')),
            formFields: $formFields,
            headers: $headers,
            authUsername: $this->nullableString($auth, 'username', 'license_import.source.auth.username'),
            authPassword: $this->nullableString($auth, 'password', 'license_import.source.auth.password'),
            timeoutSeconds: $this->intOrDefault($http, 'timeout', 30, 'license_import.http.timeout'),
            connectTimeoutSeconds: $this->intOrDefault($http, 'connect_timeout', 10, 'license_import.http.connect_timeout'),
            retryTimes: $this->intOrDefault($retries, 'times', 0, 'license_import.http.retries.times'),
            retrySleepMilliseconds: $this->intOrDefault($retries, 'sleep_ms', 0, 'license_import.http.retries.sleep_ms'),
            followRedirects: $this->boolOrDefault($http, 'allow_redirects', true, 'license_import.http.allow_redirects'),
            extraOptions: [],
            expectedContentTypes: [
                'text/html',
                'application/xhtml+xml',
                'text/plain',
            ],
            minBodyLength: 100,
            errorMarkers: [
                'erreur',
                'error',
                'maintenance',
                'acces refuse',
                'accès refusé',
                'forbidden',
                'unauthorized',
                'login',
                'identification',
                'connexion',
            ],
            requiredMarkers: [
                '<html',
                '<table',
            ],
        );
    }

    /**
     * @param array<string, mixed> $source
     */
    private function buildSourceUrl(array $source): string
    {
        $baseUrl = $this->requireNonEmptyString($source, 'base_url', 'license_import.source.base_url');
        $endpoint = $this->stringOrDefault($source, 'endpoint', '', 'license_import.source.endpoint');

        $baseUrl = rtrim($baseUrl, '/');

        if ($endpoint === '') {
            return $baseUrl;
        }

        return $baseUrl . '/' . ltrim($endpoint, '/');
    }

    /**
     * @param array<string, mixed> $headers
     * @param array<string, mixed> $http
     * @return array<string, string>
     */
    private function enrichHeadersWithUserAgent(array $headers, array $http): array
    {
        foreach ($headers as $key => $value) {
            if (!is_string($key) || trim($key) === '') {
                throw new InvalidArgumentException('The license_import.http.headers keys must be non-empty strings.');
            }

            if (!is_string($value)) {
                throw new InvalidArgumentException(sprintf(
                    'The license_import.http.headers value for "%s" must be a string.',
                    $key,
                ));
            }
        }

        $normalizedHeaders = $headers;

        $userAgent = $this->stringOrDefault($http, 'user_agent', '', 'license_import.http.user_agent');

        if ($userAgent !== '') {
            $normalizedHeaders['User-Agent'] = $userAgent;
        }

        return $normalizedHeaders;
    }

    /**
     * @param array<string, mixed> $source
     * @return array<string, mixed>
     */
    private function requireArray(array $source, string $key, string $path): array
    {
        if (!array_key_exists($key, $source)) {
            throw new InvalidArgumentException(sprintf('Missing required config key: %s.', $path));
        }

        if (!is_array($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be an array.', $path));
        }

        return $source[$key];
    }

    /**
     * @param array<string, mixed> $source
     */
    private function requireNonEmptyString(array $source, string $key, string $path): string
    {
        if (!array_key_exists($key, $source)) {
            throw new InvalidArgumentException(sprintf('Missing required config key: %s.', $path));
        }

        if (!is_string($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be a string.', $path));
        }

        $value = trim($source[$key]);

        if ($value === '') {
            throw new InvalidArgumentException(sprintf('Config key "%s" must not be empty.', $path));
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $source
     */
    private function stringOrDefault(array $source, string $key, string $default, string $path): string
    {
        if (!array_key_exists($key, $source) || $source[$key] === null) {
            return $default;
        }

        if (!is_string($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be a string.', $path));
        }

        $value = trim($source[$key]);

        if ($value === '') {
            return $default;
        }

        return $value;
    }

    /**
     * @param array<string, mixed> $source
     */
    private function nullableString(array $source, string $key, string $path): ?string
    {
        if (!array_key_exists($key, $source) || $source[$key] === null) {
            return null;
        }

        if (!is_string($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be a string or null.', $path));
        }

        $value = trim($source[$key]);

        return $value === '' ? null : $value;
    }

    /**
     * @param array<string, mixed> $source
     */
    private function intOrDefault(array $source, string $key, int $default, string $path): int
    {
        if (!array_key_exists($key, $source) || $source[$key] === null) {
            return $default;
        }

        if (!is_int($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be an integer.', $path));
        }

        return $source[$key];
    }

    /**
     * @param array<string, mixed> $source
     */
    private function boolOrDefault(array $source, string $key, bool $default, string $path): bool
    {
        if (!array_key_exists($key, $source) || $source[$key] === null) {
            return $default;
        }

        if (!is_bool($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be a boolean.', $path));
        }

        return $source[$key];
    }

    /**
     * @param array<string, mixed> $source
     */
    private function arrayOrDefault(array $source, string $key, array $default, string $path): array
    {
        if (!array_key_exists($key, $source) || $source[$key] === null) {
            return $default;
        }

        if (!is_array($source[$key])) {
            throw new InvalidArgumentException(sprintf('Config key "%s" must be an array.', $path));
        }

        return $source[$key];
    }
}