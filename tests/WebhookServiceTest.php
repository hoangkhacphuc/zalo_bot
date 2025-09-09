<?php

declare(strict_types=1);

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\Handler;
use Hoangkhacphuc\ZaloBot\Contracts\MessageDispatcher as MessageDispatcherContract;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\Contracts\Validator;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\Service\WebhookService;

class FakeValidator implements Validator
{
    public array $validated = [];

    public function validate(array $payload, array $headers, BotConfig $botConfig): void
    {
        $this->validated[] = compact('payload', 'headers');
    }
}

class FakeRepository implements Repository
{
    public array $saved = [];

    public function save(Message $message): void
    {
        $this->saved[] = $message;
    }
}

class FakeDispatcher implements MessageDispatcherContract
{
    public array $handlers = [];

    public array $dispatched = [];

    public function registerHandler(Handler $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function dispatch(Message $message): void
    {
        $this->dispatched[] = $message;
    }
}

it('validates, saves and dispatches message', function () {
    $service = new WebhookService(
        new BotConfig('TOKEN', 'URL', 'SECRET'),
        $repo = new FakeRepository,
        $validator = new FakeValidator,
        $dispatcher = new FakeDispatcher,
    );

    $payload = ['event_name' => 'user_send_text', 'message' => ['msg_id' => '1']];
    $headers = ['X-ZALO-SIGNATURE' => '...'];

    $service->handle($payload, $headers);

    expect($validator->validated)->not()->toBeEmpty();
    expect($repo->saved)->toHaveCount(1);
    expect($dispatcher->dispatched)->toHaveCount(1);
});
