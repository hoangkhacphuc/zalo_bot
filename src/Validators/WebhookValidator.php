<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Validators;

use Hoangkhacphuc\ZaloBot\BotConfig;
use Hoangkhacphuc\ZaloBot\Contracts\Validator;
use Hoangkhacphuc\ZaloBot\Exceptions\InvalidWebhookHeaderException;
use Hoangkhacphuc\ZaloBot\Exceptions\WebhookConfigException;
use InvalidArgumentException;

class WebhookValidator implements Validator
{
    /**
     * @throws InvalidWebhookHeaderException
     * @throws WebhookConfigException
     */
    public function validate(array $payload, array $headers, BotConfig $botConfig): void
    {
        if (empty($payload)) {
            throw new InvalidArgumentException('Payload is empty or invalid JSON.', 400);
        }
        if (empty($headers)) {
            throw new InvalidArgumentException('Headers are missing.', 400);
        }
        $resWebhookUrl = empty($headers['Host']) ? null : $headers['Host'];
        $resWebhookSecret = empty($headers['X-Bot-Api-Secret-Token']) ? null : $headers['X-Bot-Api-Secret-Token'];
        $contentType = empty($headers['Content-Type']) ? null : $headers['Content-Type'];
        if (empty($resWebhookUrl) || empty($resWebhookSecret)) {
            throw new InvalidWebhookHeaderException('Missing required webhook headers', 401);
        }
        if ($botConfig->getWebhookUrl() !== $resWebhookUrl) {
            throw new WebhookConfigException('Webhook URL does not match the configured URL.', 401);
        }
        if ($botConfig->getWebhookSecret() !== $resWebhookSecret) {
            throw new WebhookConfigException('Webhook secret token does not match the configured token.', 401);
        }
        if (empty($contentType) || stripos($contentType, 'application/json') === false) {
            throw new InvalidWebhookHeaderException('Invalid content type. Only application/json is allowed.', 415);
        }
    }
}
