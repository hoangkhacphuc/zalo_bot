<?php 
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot;

use GuzzleHttp\Exception\GuzzleException;
use Hoangkhacphuc\ZaloBot\Contracts\ZaloBotInterface;
use Hoangkhacphuc\ZaloBot\DTO\InfoMe;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\DTO\MessageResponse;
use Hoangkhacphuc\ZaloBot\DTO\Webhook;
use Hoangkhacphuc\ZaloBot\Enums\ZaloBotMethod;
use Hoangkhacphuc\ZaloBot\Exceptions\BadRequestException;
use Hoangkhacphuc\ZaloBot\Exceptions\GetMeException;
use Hoangkhacphuc\ZaloBot\Exceptions\InternalServerException;
use Hoangkhacphuc\ZaloBot\Exceptions\NotAnArrayException;
use Hoangkhacphuc\ZaloBot\Exceptions\NotFoundException;
use Hoangkhacphuc\ZaloBot\Exceptions\QuotaExceededException;
use Hoangkhacphuc\ZaloBot\Exceptions\RequestTimeoutException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendChatActionException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendMessageException;
use Hoangkhacphuc\ZaloBot\Exceptions\DeleteWebhookException;
use Hoangkhacphuc\ZaloBot\Exceptions\GetUpdatesException;
use Hoangkhacphuc\ZaloBot\Exceptions\GetWebhookInfoException;
use Hoangkhacphuc\ZaloBot\Exceptions\HttpException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendPhotoException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendStickerException;
use Hoangkhacphuc\ZaloBot\Exceptions\SetWebhookException;
use Hoangkhacphuc\ZaloBot\Exceptions\UnauthorizedException;
use Hoangkhacphuc\ZaloBot\Support\HttpClient;
use Throwable;

class ZaloBot implements ZaloBotInterface
{
    protected string $accessToken;
    protected HttpClient $http;

    /**
     * Initialize ZaloBot client with API access token.
     *
     * @param string $accessToken The access token of the Zalo Bot.
     */
    public function __construct(string $accessToken)
    {
        $this->setAccessToken($accessToken);
    }

    /**
     * Get basic information about the bot.
     *
     * @return InfoMe Bot information object.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws Throwable                   If there is an error during the process.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws GetMeException              If the server returns an error when fetching bot info.
     */
    public function getMe(): InfoMe
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::GET_ME));
        if (empty($response['ok'])) {
            throw new GetMeException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return InfoMe::fromArray($response['result'] ?? []);
    }

    /**
     * Retrieve updates (messages or events) sent to the bot.
     *
     * @return Message Message object containing the update data.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws Throwable                   If there is an error during the process.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws GetUpdatesException         If the server returns an error when fetching updates.
     */
    public function getUpdates(): Message
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::GET_UPDATES));

        if (empty($response['ok'])) {
            throw new GetUpdatesException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return Message::fromArray($response['result'] ?? []);
    }

    /**
     * Register a webhook to receive incoming events.
     *
     * @param string $url Webhook URL.
     * @param string $secretToken Secret token used to validate requests.
     *
     * @return Webhook Webhook information object.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws SetWebhookException         If the server returns an error when setting the webhook.
     */
    public function setWebhook(string $url, string $secretToken): Webhook
    {
        $payload = [
            'url' => $url,
            'secret_token' => $secretToken,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SET_WEBHOOK), $payload);

        if (empty($response['ok'])) {
            throw new SetWebhookException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return Webhook::fromArray($response['result'] ?? []);
    }

    /**
     * Delete the currently registered webhook.
     *
     * @return Webhook Deleted webhook info.
     * @throws Throwable                   If there is an error during the process.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws DeleteWebhookException      If the server returns an error when deleting the webhook.
     */
    public function deleteWebhook(): Webhook
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::DELETE_WEBHOOK));

        if (empty($response['ok'])) {
            throw new DeleteWebhookException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return Webhook::fromArray($response['result'] ?? []);
    }

    /**
     * Get information about the currently set webhook.
     *
     * @return Webhook Webhook information object.
     * @throws GetWebhookInfoException     If the server returns an error when fetching webhook info.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws Throwable                   If there is an error during the process.
     */
    public function getWebhookInfo(): Webhook
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::GET_WEBHOOK_INFO));

        if (empty($response['ok'])) {
            throw new GetWebhookInfoException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return Webhook::fromArray($response['result'] ?? []);
    }

    /**
     * Send a plain text message to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $message The message content.
     *
     * @return MessageResponse The response object from Zalo API.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws Throwable                   If there is an error during the process.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws SendMessageException        If the server returns an error when sending the message.
     */
    public function sendMessage(string $chatId, string $message): MessageResponse
    {
        $payload = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_MESSAGE), $payload);

        if (empty($response['ok'])) {
            throw new SendMessageException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return MessageResponse::fromArray($response['result'] ?? []);
    }

    /**
     * Send a photo message to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $photoUrl Direct link to the photo.
     * @param string $caption Optional caption text.
     *
     * @return MessageResponse The response object from Zalo API.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws Throwable                   If there is an error during the process.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws SendPhotoException          If the server returns an error when sending the photo.
     */
    public function sendPhoto(string $chatId, string $photoUrl, string $caption = ''): MessageResponse
    {
        $payload = [
            'chat_id' => $chatId,
            'photo' => $photoUrl,
            'caption' => $caption,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_PHOTO), $payload);

        if (empty($response['ok'])) {
            throw new SendPhotoException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return MessageResponse::fromArray($response['result'] ?? []);
    }

    /**
     * Send a sticker to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $stickerId Sticker ID (refer to https://stickers.zaloapp.com/).
     *
     * @return MessageResponse The response object from Zalo API.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws Throwable                   If there is an error during the process.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws SendStickerException        If the server returns an error when sending the sticker.
     */
    public function sendSticker(string $chatId, string $stickerId): MessageResponse
    {
        $payload = [
            'chat_id' => $chatId,
            'sticker' => $stickerId,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_STICKER), $payload);

        if (empty($response['ok'])) {
            throw new SendStickerException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return MessageResponse::fromArray($response['result'] ?? []);
    }

    /**
     * Send a chat action (e.g., typing indicator) to a user.
     *
     * @param string $chatId Recipient's chat ID (user ID).
     * @param string $action The action type (e.g., "typing").
     *
     * @return bool True if action was successfully sent.
     * @throws GuzzleException             If there is a network or request error.
     * @throws HttpException               If the response contains invalid JSON or an HTTP error (status >= 400).
     * @throws BadRequestException         If the server returns a 400 Bad Request response.
     * @throws UnauthorizedException       If the server returns a 401 Unauthorized response.
     * @throws InternalServerException     If the server returns a 500 Internal Server Error.
     * @throws NotFoundException           If the server returns a 404 Not Found response.
     * @throws RequestTimeoutException     If the request times out.
     * @throws QuotaExceededException      If the request exceeds API quota limits.
     * @throws Throwable                   If there is an error during the process.
     * @throws NotAnArrayException         If the response is not an array.
     * @throws SendChatActionException     If the server returns an error when sending the chat action.
     */
    public function sendChatAction(string $chatId, string $action): bool
    {
        $payload = [
            'chat_id' => $chatId,
            'action' => $action,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_CHAT_ACTION), $payload);

        if (empty($response['ok'])) {
            throw new SendChatActionException(
                $response['description'] ?? 'Unknown error',
                (int)($response['error_code'] ?? 0),
                $response
            );
        }

        return true;
    }

    /**
     * Set the access token and initialize the HTTP client.
     *
     * @param string $accessToken The access token of the Zalo Bot.
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
        $this->http = new HttpClient();
    }

    /**
     * Build the full API endpoint URL for a given method.
     *
     * @param ZaloBotMethod $method The API method to call.
     *
     * @return string The full request URL.
     */
    private function getUrl(ZaloBotMethod $method): string
    {
        return sprintf(
            'https://bot-api.zapps.me/bot%s/%s',
            $this->accessToken,
            $method->value
        );
    }
}
