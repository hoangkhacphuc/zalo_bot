<?php 
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\DTO;

/**
 * Data Transfer Object (DTO) representing an outgoing message.
 */
class MessageResponse
{
    /**
     * MessageResponse constructor.
     *
     * @param string $messageId  Unique identifier of the message.
     * @param string $date       Message creation timestamp (string or Unix time).
     */
    public function __construct(
        public readonly string $messageId,
        public readonly string $date,
    ) {}

    /**
     * Create a MessageResponse object from an associative array (typically API response).
     *
     * @param array $data Raw message response data.
     *
     * @return self
     */
    public static function fromArray(array $data): self
    {
        $messageId = !empty($data['message_id']) ? (string) $data['message_id'] : '';
        $dateValue = !empty($data['date']) ? $data['date'] : time();
        $date = (string) $dateValue;

        return new self(
            messageId: $messageId,
            date: $date,
        );
    }

}
