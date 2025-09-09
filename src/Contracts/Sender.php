<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\DTO\Message;

interface Sender
{
    public function send(Message $message): mixed;
}
