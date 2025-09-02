<?php
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot;

use Hoangkhacphuc\ZaloBot\DTO\Message;
use Hoangkhacphuc\ZaloBot\Exceptions\InvalidRequestContentTypeException;
use Hoangkhacphuc\ZaloBot\Exceptions\InvalidRequestMethodException;
use Hoangkhacphuc\ZaloBot\Exceptions\PostDataEmptyException;
use Hoangkhacphuc\ZaloBot\Exceptions\WebhookConfigException;
use Throwable;

/**
 * Class WebhookHandler
 *
 * Handles incoming webhook requests from Zalo.
 * Validates HTTP method, content type, and JSON body.
 * Converts the request payload into a Message DTO.
 */
class WebhookHandler
{
    /**
     * The Message object created from the webhook payload.
     *
     * @var Message
     */
    protected Message $message;

    /**
     * WebhookHandler constructor.
     *
     * Validates the incoming HTTP request and initializes the Message object.
     *
     * @param array|null $server Simulate $_SERVER, e.g. ['REQUEST_METHOD' => 'POST', 'CONTENT_TYPE' => 'application/json']
     * @param string|null $body Raw JSON string (instead of reading php://input)
     *
     * @throws InvalidRequestMethodException            If the HTTP request method is not POST.
     * @throws InvalidRequestContentTypeException       If the Content-Type header is not application/json.
     * @throws PostDataEmptyException                   If the request body is empty or invalid JSON.
     * @throws Throwable                                For any unexpected error during message initialization.
     */
    public function __construct(string $webhookUrl, string $webhookSecret, ?array $server = null, ?string $body = null)
    {
        $server = $server ?? $_SERVER;
        $body = $body ?? file_get_contents('php://input');

        if ($server['REQUEST_METHOD'] !== 'POST') {
            throw new InvalidRequestMethodException('Invalid request method. Only POST requests are allowed.', 405);
        }
        if (empty($server['CONTENT_TYPE']) || stripos($server['CONTENT_TYPE'], 'application/json') === false) {
            throw new InvalidRequestContentTypeException('Invalid content type. Only application/json is allowed.', 415);
        }

        $headers = getallheaders();
        if (empty($webhookUrl) || empty($webhookSecret)) {
            throw new WebhookConfigException('Webhook URL or secret token is not configured.', 401);
        }
        if ($webhookUrl !== $headers['Host']) {
            throw new WebhookConfigException('Webhook URL does not match the configured URL.', 401);
        }
        if ($webhookSecret !== $headers['X-Bot-Api-Secret-Token']) {
            throw new WebhookConfigException('Webhook secret token does not match the configured token.', 401);
        }

        $data = json_decode($body, true);

        if (empty($data)) {
            throw new PostDataEmptyException('Empty request body.', 400);
        }

        $this->message = Message::fromArray($data);
    }

    /**
     * Get the Message object created from the webhook payload.
     *
     * @return Message The message DTO representing the incoming request.
     */
    public function getMessage(): Message
    {
        return $this->message;
    }
}
