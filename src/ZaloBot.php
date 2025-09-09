<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot;

use Hoangkhacphuc\ZaloBot\Contracts\EndpointResolver;
use Hoangkhacphuc\ZaloBot\Contracts\HttpClient;
use Hoangkhacphuc\ZaloBot\Contracts\ZaloBotInterface;
use Hoangkhacphuc\ZaloBot\DTO\InfoMe;
use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\DTO\MessageResponse;
use Hoangkhacphuc\ZaloBot\DTO\Webhook;
use Hoangkhacphuc\ZaloBot\Enums\ZaloBotMethod;
use Hoangkhacphuc\ZaloBot\Exceptions\DeleteWebhookException;
use Hoangkhacphuc\ZaloBot\Exceptions\GetMeException;
use Hoangkhacphuc\ZaloBot\Exceptions\GetUpdatesException;
use Hoangkhacphuc\ZaloBot\Exceptions\GetWebhookInfoException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendChatActionException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendMessageException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendPhotoException;
use Hoangkhacphuc\ZaloBot\Exceptions\SendStickerException;
use Hoangkhacphuc\ZaloBot\Exceptions\SetWebhookException;
use Hoangkhacphuc\ZaloBot\Support\DefaultEndpointResolver;

class ZaloBot implements ZaloBotInterface
{
    protected string $accessToken;

    protected HttpClient $http;

    protected EndpointResolver $endpointResolver;

    /**
     * Initialize ZaloBot client with API access token.
     *
     * @param  string  $accessToken  The access token of the Zalo Bot.
     * @param  HttpClient  $httpClient  The HTTP client implementation.
     * @param  EndpointResolver|null  $endpointResolver  The endpoint resolver.
     */
    public function __construct(string $accessToken, HttpClient $httpClient, ?EndpointResolver $endpointResolver = null)
    {
        $this->setAccessToken($accessToken);
        $this->http = $httpClient;
        $this->endpointResolver = $endpointResolver ?? new DefaultEndpointResolver;
    }

    /** Get bot info. */
    public function getMe(): InfoMe
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::GET_ME));
        if (empty($response['ok'])) {
            throw new GetMeException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return InfoMe::fromArray($response['result'] ?? []);
    }

    /** Get incoming updates. */
    public function getUpdates(): Message
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::GET_UPDATES));

        if (empty($response['ok'])) {
            throw new GetUpdatesException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return Message::fromArray($response['result'] ?? []);
    }

    /** Set webhook. */
    public function setWebhook(string $url, string $secretToken): Webhook
    {
        $payload = [
            'url'          => $url,
            'secret_token' => $secretToken,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SET_WEBHOOK), $payload);

        if (empty($response['ok'])) {
            throw new SetWebhookException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return Webhook::fromArray($response['result'] ?? []);
    }

    /** Delete webhook. */
    public function deleteWebhook(): Webhook
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::DELETE_WEBHOOK));

        if (empty($response['ok'])) {
            throw new DeleteWebhookException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return Webhook::fromArray($response['result'] ?? []);
    }

    /** Get webhook info. */
    public function getWebhookInfo(): Webhook
    {
        $response = $this->http->post($this->getUrl(ZaloBotMethod::GET_WEBHOOK_INFO));

        if (empty($response['ok'])) {
            throw new GetWebhookInfoException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return Webhook::fromArray($response['result'] ?? []);
    }

    /** Send text message. */
    public function sendMessage(string $chatId, string $message): MessageResponse
    {
        $payload = [
            'chat_id' => $chatId,
            'text'    => $message,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_MESSAGE), $payload);

        if (empty($response['ok'])) {
            throw new SendMessageException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return MessageResponse::fromArray($response['result'] ?? []);
    }

    /** Send photo message. */
    public function sendPhoto(string $chatId, string $photoUrl, string $caption = ''): MessageResponse
    {
        $payload = [
            'chat_id' => $chatId,
            'photo'   => $photoUrl,
            'caption' => $caption,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_PHOTO), $payload);

        if (empty($response['ok'])) {
            throw new SendPhotoException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return MessageResponse::fromArray($response['result'] ?? []);
    }

    /** Send sticker. */
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
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return MessageResponse::fromArray($response['result'] ?? []);
    }

    /** Send chat action. */
    public function sendChatAction(string $chatId, string $action): bool
    {
        $payload = [
            'chat_id' => $chatId,
            'action'  => $action,
        ];

        $response = $this->http->post($this->getUrl(ZaloBotMethod::SEND_CHAT_ACTION), $payload);

        if (empty($response['ok'])) {
            throw new SendChatActionException(
                $response['description'] ?? 'Unknown error',
                (int) ($response['error_code'] ?? 0),
                $response
            );
        }

        return true;
    }

    /** Set access token. */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /** Build request URL. */
    private function getUrl(ZaloBotMethod $method): string
    {
        return $this->endpointResolver->resolve($this->accessToken, $method);
    }
}
