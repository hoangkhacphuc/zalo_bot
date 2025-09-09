<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\DTO\Message;

interface Handler
{
    public function canHandle(Message $message): bool;

    public function handle(Message $message): void;
}
