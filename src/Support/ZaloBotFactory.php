<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Support;

use Hoangkhacphuc\ZaloBot\Contracts\EndpointResolver;
use Hoangkhacphuc\ZaloBot\Contracts\HttpClient as HttpClientContract;
use Hoangkhacphuc\ZaloBot\ZaloBot;

class ZaloBotFactory
{
    public static function create(string $accessToken, ?HttpClientContract $httpClient = null, ?EndpointResolver $endpointResolver = null): ZaloBot
    {
        $client = $httpClient ?? new HttpClient;

        return new ZaloBot($accessToken, $client, $endpointResolver);
    }
}
