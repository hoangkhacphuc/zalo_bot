<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\DTO\Message;

interface Repository
{
    public function save(Message $message): void;
}
