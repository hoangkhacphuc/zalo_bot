<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\BotConfig;

interface Validator
{
    public function validate(array $payload, array $headers, BotConfig $botConfig): void;
}
