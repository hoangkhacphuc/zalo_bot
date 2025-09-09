<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Support;

use Hoangkhacphuc\ZaloBot\Contracts\EndpointResolver;
use Hoangkhacphuc\ZaloBot\Enums\ZaloBotMethod;

class DefaultEndpointResolver implements EndpointResolver
{
    public function resolve(string $accessToken, ZaloBotMethod $method): string
    {
        return sprintf('https://bot-api.zapps.me/bot%s/%s', $accessToken, $method->value);
    }
}
