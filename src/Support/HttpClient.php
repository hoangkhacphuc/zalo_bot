<?php 
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Support;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Hoangkhacphuc\ZaloBot\Exceptions\BadRequestException;
use Hoangkhacphuc\ZaloBot\Exceptions\HttpException;
use Hoangkhacphuc\ZaloBot\Exceptions\InternalServerException;
use Hoangkhacphuc\ZaloBot\Exceptions\NotAnArrayException;
use Hoangkhacphuc\ZaloBot\Exceptions\NotFoundException;
use Hoangkhacphuc\ZaloBot\Exceptions\QuotaExceededException;
use Hoangkhacphuc\ZaloBot\Exceptions\RequestTimeoutException;
use Hoangkhacphuc\ZaloBot\Exceptions\UnauthorizedException;

/**
 * HttpClient is a helper class for sending HTTP requests to the Zalo API.
 *
 * It wraps GuzzleHttp\Client and provides a simplified interface for sending POST requests
 * with JSON payloads and handling JSON responses and HTTP errors.
 */
class HttpClient
{
    /**
     * Guzzle HTTP client instance used for sending requests.
     *
     * @var Client
     */
    protected Client $client;

    /**
     * Initialize a new HttpClient instance.
     *
     * Configures default headers, timeout, and disables automatic HTTP errors.
     */
    public function __construct()
    {
        $this->client = new Client([
            'headers' => [
                'Accept' => 'application/json',
                'Accept-Charset' => 'utf-8',
                'Accept-Encoding' => 'gzip, deflate, br',
                'Content-Type' => 'application/json'
            ],
            'http_errors' => false,
            'timeout' => 30,
        ]);
    }

    /**
     * Send a POST request to the specified URL with JSON data.
     *
     * @param string $url The URL to send the POST request to.
     * @param array $data JSON data to include in the request body.
     * @param array $headers Optional additional headers to include in the request.
     *
     * @return array Decoded JSON response as an associative array.
     *
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws NotAnArrayException         If the response is not an array.
     */
    public function post(string $url, array $data = [], array $headers = []): array
    {
        $response = $this->client->post($url, [
            'json' => $data,
            'headers' => $headers
        ]);
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();

        $decoded = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new HttpException("Invalid JSON response", $statusCode);
        }

        if (! is_array($decoded)) {
            throw new NotAnArrayException("Response is not an array", $statusCode, $decoded);
        }

        switch ($statusCode) {
            case 400:
                throw new BadRequestException("Bad request - wrong path or invalid API Name", 400, $decoded);
            case 401:
                throw new UnauthorizedException("Unauthorized - invalid or missing Zalo Bot token", 401, $decoded);
            case 403:
                throw new InternalServerException("Internal server error", 403, $decoded);
            case 404:
                throw new NotFoundException("Not Found - the requested resource could not be found", 404, $decoded);
            case 408:
                throw new RequestTimeoutException("Request Timeout - The server timed out waiting for the request", 408, $decoded);
            case 429:
                throw new QuotaExceededException("Quota exceeded - Exceeded API usage limit", 429, $decoded);
        }

        if ($statusCode >= 400) {
            throw new HttpException("HTTP error: $statusCode", $statusCode, $decoded);
        }

        return $decoded;
    }
}
