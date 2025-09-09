<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot;

class BotConfig
{
    public function __construct(
        protected string $accessToken,
        protected string $webhookUrl,
        protected string $webhookSecret,
    ) {}

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getWebhookUrl(): string
    {
        return $this->webhookUrl;
    }

    public function getWebhookSecret(): string
    {
        return $this->webhookSecret;
    }
}
