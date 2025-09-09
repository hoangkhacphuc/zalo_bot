<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Handler;

use Hoangkhacphuc\ZaloBot\Contracts\Handler;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\Enums\MessageType;

abstract class StickerMessage implements Handler
{
    public function canHandle(Message $message): bool
    {
        return $message->getType() == MessageType::STICKER;
    }

    abstract public function handle(Message $message): void;
}
