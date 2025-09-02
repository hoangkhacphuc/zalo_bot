<?php 
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Hoangkhacphuc\ZaloBot\DTO\InfoMe;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\DTO\MessageResponse;
use Hoangkhacphuc\ZaloBot\DTO\Webhook;
use Hoangkhacphuc\ZaloBot\Exceptions\SendMessageException;
use Hoangkhacphuc\ZaloBot\Exceptions\HttpException;

interface ZaloBotInterface
{
    /**
     * Get basic information about the bot (e.g., name, ID).
     *
     * @return InfoMe Bot information object.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function getMe(): InfoMe;

    /**
     * Retrieve updates (messages/events) sent to the bot.
     *
     * @return Message Message object containing the update data.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function getUpdates(): Message;

    /**
     * Register a webhook to receive incoming events from Zalo server.
     *
     * @param string $url Webhook endpoint URL.
     * @param string $secretToken Secret token used to validate webhook requests.
     *
     * @return Webhook Webhook information object.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function setWebhook(string $url, string $secretToken): Webhook;

    /**
     * Remove the currently registered webhook.
     *
     * @return Webhook Deleted webhook information.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function deleteWebhook(): Webhook;

    /**
     * Get details about the currently set webhook (status, URL, etc.).
     *
     * @return Webhook Webhook information object.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function getWebhookInfo(): Webhook;

    /**
     * Send a plain text message to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $message The text message content.
     *
     * @return MessageResponse API response object.
     * @throws HttpException
     * @throws GuzzleException
     * @throws SendMessageException
     */
    public function sendMessage(string $chatId, string $message): MessageResponse;

    /**
     * Send a photo message to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $photoUrl URL of the photo to send.
     * @param string $caption Optional caption for the photo.
     *
     * @return MessageResponse API response object.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function sendPhoto(string $chatId, string $photoUrl, string $caption = ''): MessageResponse;

    /**
     * Send a sticker to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $stickerId Sticker ID (from https://stickers.zaloapp.com/).
     *
     * @return MessageResponse API response object.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function sendSticker(string $chatId, string $stickerId): MessageResponse;

    /**
     * Send a chat action (e.g., typing indicator) to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $action The action type (e.g., "typing").
     *
     * @return bool True if the action was successfully sent.
     * @throws HttpException
     * @throws GuzzleException
     */
    public function sendChatAction(string $chatId, string $action): bool;
}
