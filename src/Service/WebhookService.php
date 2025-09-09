<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Service;

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\MessageDispatcher as MessageDispatcherContract;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\Contracts\Validator;
use Hoangkhacphuc\ZaloBot\DTO\Message;

class WebhookService
{
    public function __construct(
        protected BotConfig $botConfig,
        protected Repository $repository,
        protected Validator $validator,
        protected MessageDispatcherContract $dispatcher
    ) {}

    /** Handle an incoming webhook payload. */
    public function handle(array $payload, array $headers): void
    {
        $this->validator->validate($payload, $headers, $this->botConfig);
        $message = Message::fromArray($payload);
        $this->repository->save($message);
        $this->dispatcher->dispatch($message);
    }
}
