<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\Repository;
use Hoangkhacphuc\ZaloBot\Contracts\ZaloBotInterface;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\Handler\ImageMessage;
use Hoangkhacphuc\ZaloBot\Handler\StickerMessage;
use Hoangkhacphuc\ZaloBot\Handler\TextMessage;
use Hoangkhacphuc\ZaloBot\Service\MessageDispatcher;
use Hoangkhacphuc\ZaloBot\Service\WebhookServiceFactory;
use Hoangkhacphuc\ZaloBot\Support\ZaloBotFactory;

$payloads = json_decode(file_get_contents('php://input'), true);
$headers = getallheaders();

class ReplyTextHandler extends TextMessage
{
    public function __construct(private ZaloBotInterface $bot) {}

    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', '>>> User send ['.$message->chatId.'] Text received: '.$message->content.PHP_EOL);
        if ($message->content !== '' && $message->chatId !== '') {
            $this->bot->sendMessage($message->chatId, $message->content);
        }
    }
}

class ReplyImageHandler extends ImageMessage
{
    public function __construct(private ZaloBotInterface $bot) {}

    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', '>>> User send ['.$message->chatId.'] Image received: '.$message->photoUrl.PHP_EOL);
        if ($message->photoUrl !== '' && $message->chatId !== '') {
            $this->bot->sendPhoto($message->chatId, $message->photoUrl);
        }
    }
}

class ReplyStickerHandler extends StickerMessage
{
    public function __construct(private ZaloBotInterface $bot) {}

    public function handle(Message $message): void
    {
        file_put_contents('php://stderr', '>>> User send ['.$message->chatId.'] Sticker received: '.$message->stickerId.PHP_EOL);
        if ($message->stickerId !== '' && $message->chatId !== '') {
            $this->bot->sendSticker($message->chatId, $message->stickerId);
        }
    }
}

class MessageRepository implements Repository
{
    public function save(Message $message): void {}
}

$botConfig = new BotConfig(
    'ZALO_ACCESS_TOKEN',
    'WEBHOOK_URL', // example.com
    'WEBHOOK_SECRET'
);

$bot = ZaloBotFactory::create($botConfig->getAccessToken());

$dispatcher = new MessageDispatcher;
$dispatcher->registerHandler(new ReplyTextHandler($bot));
$dispatcher->registerHandler(new ReplyImageHandler($bot));
$dispatcher->registerHandler(new ReplyStickerHandler($bot));

$webhookService = WebhookServiceFactory::create(
    $botConfig,
    new MessageRepository,
    dispatcher: $dispatcher,
);

$webhookService->handle($payloads, $headers);
