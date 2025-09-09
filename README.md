# Hoangkhacphuc/ZaloBot

A lightweight, framework-agnostic PHP SDK to build Zalo chatbots with safety, testability, and developer ergonomics.

## Features

- Handle Zalo messages and events
- Type-safe DTOs: `Message`, `Webhook`, `InfoMe`, `MessageResponse`
- SOLID architecture with interfaces and DI
- Works in plain PHP or Laravel
- Rich exceptions for robust error handling

## Why Use This Library?
- Framework-independent: works anywhere
- Type-safe: DTOs ensure validated data
- Testable: dependency-injected collaborators and factories
- Extensible: swap implementations via interfaces
- Production-ready: explicit errors and safe parsing

## Requirements

- PHP 8.1 or higher
- Composer
- Optional: Laravel 9+ for Laravel integration

## Installation

Install via Composer:

```bash
composer require hoangkhacphuc/zalo_bot
```

## Quick Start (Plain PHP)

```php
<?php
require_once 'vendor/autoload.php';

use Hoangkhacphuc\ZaloBot\Support\ZaloBotFactory;

$bot = ZaloBotFactory::create('<zalo-bot-access-token>');
$me = $bot->getMe();
echo $me->name;
```

## Handle Webhooks

```php
<?php
require_once 'vendor/autoload.php';

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\Handler\TextMessage;
use Hoangkhacphuc\ZaloBot\Service\WebhookServiceFactory;
use Hoangkhacphuc\ZaloBot\Service\MessageDispatcher;

$payloads = json_decode(file_get_contents('php://input'), true);
$headers = getallheaders();

class MessageRepository implements Repository
{
    public function save(Message $message): void
    {
        // Save message to database or log
    }
}

class TextMessageHandler extends TextMessage
{
    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', 'Text: '.$message->content.PHP_EOL);
    }
}

$botConfig = new BotConfig(
    '<zalo-access-token>',
    '<webhook-url>',
    '<webhook-secret>'
);

$dispatcher = new MessageDispatcher;
$dispatcher->registerHandler(new TextMessageHandler);

$webhookService = WebhookServiceFactory::create(
    $botConfig,
    new MessageRepository,
    dispatcher: $dispatcher,
);

// Handle
$webhookService->handle($payloads, $headers);
```

## Laravel Integration

1. Publish config:

```bash
php artisan vendor:publish --tag=zalobot-config
```

2. Set environment variables in `.env`:

```env
ZALO_ACCESS_TOKEN=your-token
ZALO_WEBHOOK_URL=https://example.com/webhook
ZALO_WEBHOOK_SECRET=your-secret
```

3. Use `WebhookService` via container:

```php
use Hoangkhacphuc\ZaloBot\Service\WebhookService;

Route::post('/zalo/webhook', function (Request $request, WebhookService $service) {
    $service->handle($request->all(), $request->headers->all());
    return response()->json(['ok' => true]);
});
```

## Testing
- Pest tests included (`tests/`).
- Run: `composer test`

## Configuration & Environment
- Use `BotConfig($accessToken, $webhookUrl, $webhookSecret)`.
- For Laravel, manage via `config/zalobot.php` and `.env`.

## Error Handling
The SDK throws domain-specific exceptions like `HttpException`, `UnauthorizedException`, `SendMessageException`.

## Migration
This is the first stable release (v1.0.0). No migration needed.

## License
MIT License. See the [LICENSE](LICENSE) file for details.