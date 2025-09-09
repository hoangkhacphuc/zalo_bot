<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Service;

use Hoangkhacphuc\ZaloBot\Contracts\Handler;
use Hoangkhacphuc\ZaloBot\Contracts\MessageDispatcher as MessageDispatcherContract;
use Hoangkhacphuc\ZaloBot\DTO\Message;

class MessageDispatcher implements MessageDispatcherContract
{
    private array $handlers = [];

    public function registerHandler(Handler $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function dispatch(Message $message): void
    {
        foreach ($this->handlers as $handler) {
            if ($handler->canHandle($message)) {
                $handler->handle($message);
                break;
            }
        }
    }
}
