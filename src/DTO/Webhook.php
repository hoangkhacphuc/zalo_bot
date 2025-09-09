<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\DTO;

/**
 * Data Transfer Object (DTO) representing webhook information.
 */
class Webhook
{
    /**
     * Webhook constructor.
     *
     * @param  string  $url  Webhook URL.
     * @param  string  $updatedAt  Timestamp of the last update to the webhook.
     */
    public function __construct(
        public readonly string $url,
        public readonly string $updatedAt,
    ) {}

    /**
     * Create a Webhook object from an associative array.
     *
     * @param  array  $data  API response data.
     */
    public static function fromArray(array $data): self
    {
        $url = ! empty($data['url']) ? (string) $data['url'] : '';
        $updatedAtValue = ! empty($data['updated_at']) ? $data['updated_at'] : time();
        $updatedAt = (string) $updatedAtValue;

        return new self(
            url: $url,
            updatedAt: $updatedAt,
        );
    }
}
