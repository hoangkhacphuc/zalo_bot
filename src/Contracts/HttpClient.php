<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Hoangkhacphuc\ZaloBot\Exceptions\HttpException;

interface HttpClient
{
    /**
     * Send a POST request and return decoded JSON as array.
     *
     * @throws GuzzleException
     * @throws HttpException
     */
    public function post(string $url, array $data = [], array $headers = []): array;
}
