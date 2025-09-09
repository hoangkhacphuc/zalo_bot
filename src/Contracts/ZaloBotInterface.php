<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use Hoangkhacphuc\ZaloBot\DTO\InfoMe;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\DTO\MessageResponse;
use Hoangkhacphuc\ZaloBot\DTO\Webhook;

interface ZaloBotInterface
{
    /** Get bot info. */
    public function getMe(): InfoMe;

    /** Get incoming updates. */
    public function getUpdates(): Message;

    /** Set webhook. */
    public function setWebhook(string $url, string $secretToken): Webhook;

    /** Delete webhook. */
    public function deleteWebhook(): Webhook;

    /** Get webhook info. */
    public function getWebhookInfo(): Webhook;

    /** Send text message. */
    public function sendMessage(string $chatId, string $message): MessageResponse;

    /** Send photo message. */
    public function sendPhoto(string $chatId, string $photoUrl, string $caption = ''): MessageResponse;

    /** Send sticker. */
    public function sendSticker(string $chatId, string $stickerId): MessageResponse;

    /** Send chat action. */
    public function sendChatAction(string $chatId, string $action): bool;
}
