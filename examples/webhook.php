<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\Handler\ImageMessage;
use Hoangkhacphuc\ZaloBot\Handler\StickerMessage;
use Hoangkhacphuc\ZaloBot\Handler\TextMessage;
use Hoangkhacphuc\ZaloBot\Service\MessageDispatcher;
use Hoangkhacphuc\ZaloBot\Service\WebhookServiceFactory;

$payloads = json_decode(file_get_contents('php://input'), true);
$headers = getallheaders();

class TextMessageHandler extends TextMessage
{
    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', '>>> User send ['.$message->chatId.'] Text received: '.$message->content.PHP_EOL);
    }
}

class ImageMessageHandler extends ImageMessage
{
    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', '>>> User send ['.$message->chatId.'] Image received: '.$message->photoUrl.PHP_EOL);
    }
}

class StickerMessageHandler extends StickerMessage
{
    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', '>>> User send ['.$message->chatId.'] Sticker received: '.$message->stickerId.PHP_EOL);
    }
}

class MessageRepository implements Repository
{
    public function save(Message $message): void
    {
        // Implement your logic to save the message to a database or log file
    }
}

$botConfig = new BotConfig(
    'ZALO_ACCESS_TOKEN',
    'WEBHOOK_URL', // example.com
    'WEBHOOK_SECRET'
);

$messageDispatcher = new MessageDispatcher;
$messageDispatcher->registerHandler(new TextMessageHandler);
$messageDispatcher->registerHandler(new ImageMessageHandler);
$messageDispatcher->registerHandler(new StickerMessageHandler);

$webhookService = WebhookServiceFactory::create(
    $botConfig,
    new MessageRepository,
    dispatcher: $messageDispatcher,
);

$webhookService->handle($payloads, $headers);
