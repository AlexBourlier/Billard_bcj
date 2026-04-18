<?php

declare(strict_types=1);

namespace App\Domain\LicenseImport\Fetch;

use App\Domain\LicenseImport\Fetch\Contracts\TelematFetcherInterface;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematFetchException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematInvalidResponseException;
use App\Domain\LicenseImport\Fetch\Exceptions\TelematNetworkException;
use DateTimeImmutable;
use GuzzleHttp\TransferStats;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

final class TelematHttpFetcher implements TelematFetcherInterface
{
    public function fetch(TelematFetchConfig $config): TelematFetchResult
    {
        $startedAt = new DateTimeImmutable();
        $effectiveUrl = null;

        $this->log('info', 'license_import.fetch.started', [
            'url' => $config->url,
            'method' => $config->method,
            'timeout_seconds' => $config->timeoutSeconds,
            'connect_timeout_seconds' => $config->connectTimeoutSeconds,
            'retry_times' => $config->retryTimes,
            'retry_sleep_milliseconds' => $config->retrySleepMilliseconds,
            'follow_redirects' => $config->followRedirects,
            'has_auth' => $config->authUsername !== null,
            'form_field_count' => count($config->formFields),
            'header_names' => array_keys($config->headers),
        ]);

        try {
            $request = $this->buildPendingRequest($config, $effectiveUrl);

            $response = $this->sendRequest($request, $config);

            $finalUrl = $effectiveUrl ?? $config->url;
            $contentType = $this->extractContentType($response);
            $body = (string) $response->body();

            $this->log('info', 'license_import.fetch.completed', [
                'requested_url' => $config->url,
                'final_url' => $finalUrl,
                'http_status' => $response->status(),
                'content_type' => $contentType,
                'body_length' => strlen($body),
            ]);

            $this->validateResponse($response, $config, $body, $contentType, $finalUrl);

            return new TelematFetchResult(
                html: $body,
                httpStatus: $response->status(),
                contentType: $contentType,
                finalUrl: $finalUrl,
                fetchedAt: $startedAt,
            );
        } catch (TelematInvalidResponseException $exception) {
            $this->log('warning', 'license_import.fetch.invalid_response', [
                'url' => $config->url,
                'reason' => $exception->reason,
                'context' => $exception->context,
                'message' => $exception->getMessage(),
            ]);

            throw $exception;
        } catch (ConnectionException $exception) {
            $this->log('error', 'license_import.fetch.network_error', [
                'url' => $config->url,
                'message' => $exception->getMessage(),
            ]);

            throw new TelematNetworkException(
                message: sprintf('Telemat fetch network failure for [%s].', $config->url),
                previous: $exception,
            );
        } catch (RequestException $exception) {
            $status = $exception->response?->status();

            $this->log('error', 'license_import.fetch.request_error', [
                'url' => $config->url,
                'http_status' => $status,
                'message' => $exception->getMessage(),
            ]);

            throw new TelematFetchException(
                message: sprintf(
                    'Telemat fetch request failure for [%s]%s.',
                    $config->url,
                    $status !== null ? sprintf(' with status [%d]', $status) : '',
                ),
                previous: $exception,
            );
        } catch (Throwable $exception) {
            $this->log('error', 'license_import.fetch.unexpected_error', [
                'url' => $config->url,
                'exception_class' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            throw new TelematFetchException(
                message: sprintf('Unexpected Telemat fetch failure for [%s].', $config->url),
                previous: $exception,
            );
        }
    }

    private function buildPendingRequest(TelematFetchConfig $config, ?string &$effectiveUrl): PendingRequest
    {
        $options = $this->buildRequestOptions($config, $effectiveUrl);

        $request = Http::withHeaders($config->headers)
            ->timeout($config->timeoutSeconds)
            ->connectTimeout($config->connectTimeoutSeconds)
            ->retry(
                times: $config->retryTimes,
                sleepMilliseconds: $config->retrySleepMilliseconds,
                when: function (Throwable $exception, PendingRequest $request): bool {
                    if ($exception instanceof ConnectionException) {
                        return true;
                    }

                    if ($exception instanceof RequestException && $exception->response !== null) {
                        return $exception->response->serverError() || $exception->response->status() === 429;
                    }

                    return false;
                },
                throw: true,
            )
            ->withOptions($options);

        if ($config->authUsername !== null && $config->authPassword !== null) {
            $request = $request->withBasicAuth($config->authUsername, $config->authPassword);
        }

        return $request;
    }

    private function sendRequest(PendingRequest $request, TelematFetchConfig $config): Response
    {
        $method = strtoupper($config->method);

        return match ($method) {
            'GET' => $request->get($config->url, $config->formFields),
            'POST' => $request->asForm()->post($config->url, $config->formFields),
            default => throw new InvalidArgumentException(sprintf(
                'Unsupported Telemat fetch HTTP method [%s].',
                $config->method,
            )),
        };
    }

    private function validateResponse(
        Response $response,
        TelematFetchConfig $config,
        string $body,
        ?string $contentType,
        ?string $finalUrl,
    ): void {
        $this->validateHttpStatus($response, $finalUrl);
        $this->validateContentType($config, $contentType, $response, $finalUrl);
        $this->validateBodyNotEmpty($body, $response, $finalUrl);
        $this->validateMinimumBodyLength($config, $body, $response, $finalUrl);
        $this->validateErrorMarkers($config, $body, $response, $finalUrl);
        $this->validateRequiredMarkers($config, $body, $response, $finalUrl);
    }

    private function validateHttpStatus(Response $response, ?string $finalUrl): void
    {
        if ($response->successful()) {
            return;
        }

        throw new TelematInvalidResponseException(
            message: sprintf('Telemat returned a non-success HTTP status [%d].', $response->status()),
            reason: 'http_status_not_successful',
            context: [
                'http_status' => $response->status(),
                'final_url' => $finalUrl,
            ],
        );
    }

    private function validateContentType(
        TelematFetchConfig $config,
        ?string $contentType,
        Response $response,
        ?string $finalUrl,
    ): void {
        if ($config->expectedContentTypes === []) {
            return;
        }

        if ($contentType === null) {
            throw new TelematInvalidResponseException(
                message: 'Telemat response does not contain a Content-Type header.',
                reason: 'missing_content_type',
                context: [
                    'http_status' => $response->status(),
                    'final_url' => $finalUrl,
                    'expected_content_types' => $config->expectedContentTypes,
                ],
            );
        }

        $actualMediaType = $this->extractMediaType($contentType);
        $expectedMediaTypes = array_map(
            fn (string $type): string => $this->extractMediaType($type),
            $config->expectedContentTypes,
        );

        if (in_array($actualMediaType, $expectedMediaTypes, true)) {
            return;
        }

        throw new TelematInvalidResponseException(
            message: sprintf(
                'Telemat response Content-Type [%s] is not in the expected list.',
                $contentType,
            ),
            reason: 'unexpected_content_type',
            context: [
                'http_status' => $response->status(),
                'final_url' => $finalUrl,
                'content_type' => $contentType,
                'expected_content_types' => $config->expectedContentTypes,
            ],
        );
    }

    private function validateBodyNotEmpty(string $body, Response $response, ?string $finalUrl): void
    {
        if (trim($body) !== '') {
            return;
        }

        throw new TelematInvalidResponseException(
            message: 'Telemat response body is empty.',
            reason: 'empty_body',
            context: [
                'http_status' => $response->status(),
                'final_url' => $finalUrl,
                'body_length' => strlen($body),
            ],
        );
    }

    private function validateMinimumBodyLength(
        TelematFetchConfig $config,
        string $body,
        Response $response,
        ?string $finalUrl,
    ): void {
        $bodyLength = strlen($body);

        if ($bodyLength >= $config->minBodyLength) {
            return;
        }

        throw new TelematInvalidResponseException(
            message: sprintf(
                'Telemat response body is shorter than the configured minimum [%d < %d].',
                $bodyLength,
                $config->minBodyLength,
            ),
            reason: 'body_too_short',
            context: [
                'http_status' => $response->status(),
                'final_url' => $finalUrl,
                'body_length' => $bodyLength,
                'minimum_body_length' => $config->minBodyLength,
            ],
        );
    }

    private function validateErrorMarkers(
        TelematFetchConfig $config,
        string $body,
        Response $response,
        ?string $finalUrl,
    ): void {
        if ($config->errorMarkers === []) {
            return;
        }

        $normalizedBody = mb_strtolower($body);

        foreach ($config->errorMarkers as $marker) {
            if (mb_stripos($normalizedBody, mb_strtolower($marker)) === false) {
                continue;
            }

            throw new TelematInvalidResponseException(
                message: sprintf('Telemat response contains an error marker [%s].', $marker),
                reason: 'error_marker_detected',
                context: [
                    'http_status' => $response->status(),
                    'final_url' => $finalUrl,
                    'matched_marker' => $marker,
                ],
            );
        }
    }

    private function validateRequiredMarkers(
        TelematFetchConfig $config,
        string $body,
        Response $response,
        ?string $finalUrl,
    ): void {
        if ($config->requiredMarkers === []) {
            return;
        }

        $normalizedBody = mb_strtolower($body);

        foreach ($config->requiredMarkers as $marker) {
            if (mb_stripos($normalizedBody, mb_strtolower($marker)) !== false) {
                return;
            }
        }

        throw new TelematInvalidResponseException(
            message: 'Telemat response does not contain any required marker.',
            reason: 'required_markers_missing',
            context: [
                'http_status' => $response->status(),
                'final_url' => $finalUrl,
                'required_markers' => $config->requiredMarkers,
            ],
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function buildRequestOptions(TelematFetchConfig $config, ?string &$effectiveUrl): array
    {
        $options = $config->extraOptions;
        $existingOnStats = $options['on_stats'] ?? null;

        $options['allow_redirects'] = $config->followRedirects;
        $options['on_stats'] = function (TransferStats $stats) use (&$effectiveUrl, $existingOnStats): void {
            if (is_callable($existingOnStats)) {
                $existingOnStats($stats);
            }

            $uri = $stats->getEffectiveUri();
            $effectiveUrl = $uri !== null ? (string) $uri : null;
        };

        return $options;
    }

    private function extractContentType(Response $response): ?string
    {
        $value = $response->header('Content-Type');

        if (!is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }

    private function extractMediaType(string $contentType): string
    {
        $parts = explode(';', $contentType, 2);

        return strtolower(trim($parts[0]));
    }

    /**
     * @param array<string, mixed> $context
     */
    private function log(string $level, string $message, array $context = []): void
    {
        $channel = config('license_import.observability.log_channel');

        if (is_string($channel) && trim($channel) !== '') {
            Log::channel($channel)->log($level, $message, $context);

            return;
        }

        Log::log($level, $message, $context);
    }
}