<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\DTO\Message;

interface MessageDispatcher
{
    public function registerHandler(Handler $handler): void;

    public function dispatch(Message $message): void;
}
