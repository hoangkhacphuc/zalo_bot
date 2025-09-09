<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\DTO;

/**
 * Data Transfer Object (DTO) representing bot account information.
 */
class InfoMe
{
    /**
     * @param  string  $id  Unique bot ID.
     * @param  string  $accountName  Bot account name.
     * @param  string  $accountType  Type of account (e.g., OA, user, etc.).
     * @param  bool  $canJoinGroups  Whether the bot can join groups.
     * @param  string  $displayName  Display name of the bot.
     */
    public function __construct(
        public readonly string $id,
        public readonly string $accountName,
        public readonly string $accountType,
        public readonly bool $canJoinGroups,
        public readonly string $displayName,
    ) {}

    /**
     * Create an InfoMe object from an associative array.
     *
     * @param  array  $data  API response data.
     */
    public static function fromArray(array $data): self
    {
        $id = ! empty($data['id']) ? (string) $data['id'] : '';
        $accountName = ! empty($data['account_name']) ? (string) $data['account_name'] : '';
        $accountType = ! empty($data['account_type']) ? (string) $data['account_type'] : '';
        $canJoinGroups = isset($data['can_join_groups']) && (bool) $data['can_join_groups'];
        $displayName = ! empty($data['display_name']) ? (string) $data['display_name'] : '';

        return new self(
            id: $id,
            accountName: $accountName,
            accountType: $accountType,
            canJoinGroups: $canJoinGroups,
            displayName: $displayName,
        );
    }
}
