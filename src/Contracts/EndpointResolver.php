<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\Enums\ZaloBotMethod;

interface EndpointResolver
{
    public function resolve(string $accessToken, ZaloBotMethod $method): string;
}
