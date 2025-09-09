<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\DTO;

use Hoangkhacphuc\ZaloBot\Enums\MessageEventName;
use Hoangkhacphuc\ZaloBot\Enums\MessageType;
use Throwable;

/**
 * Data Transfer Object (DTO) representing an incoming message.
 */
class Message
{
    /**
     * @param  string  $chatId  Unique chat ID (user or group).
     * @param  string  $messageId  Unique identifier of the message.
     * @param  string  $date  Message creation timestamp (string or Unix time).
     * @param  string  $displayName  Sender's display name.
     * @param  MessageEventName  $eventName  Event type (e.g., message, reaction).
     * @param  string  $content  Text content of the message.
     * @param  string  $stickerId  Sticker ID if a sticker is sent.
     * @param  string  $stickerUrl  URL to the sticker image (if any).
     * @param  string  $photoUrl  URL to the photo sent (if any).
     * @param  string  $caption  Caption of the photo (if provided).
     */
    public function __construct(
        public readonly string $chatId,
        public readonly string $messageId,
        public readonly string $date,
        public readonly string $displayName,
        public readonly MessageEventName $eventName,
        public readonly string $content,
        public readonly string $stickerId,
        public readonly string $stickerUrl,
        public readonly string $photoUrl,
        public readonly string $caption
    ) {}

    /**
     * Create a Message object from an associative array (typically API response).
     *
     * @param  array  $data  Raw message data.
     *
     * @throws Throwable If event name mapping fails.
     */
    public static function fromArray(array $data): self
    {
        $messageData = $data['message'] ?? [];

        $chatId = ! empty($messageData['chat']['id']) ? (string) $messageData['chat']['id'] : '';
        $messageId = ! empty($messageData['message_id']) ? (string) $messageData['message_id'] : '';
        $dateValue = ! empty($messageData['date']) ? $messageData['date'] : time();
        $date = is_int($dateValue) ? (string) $dateValue : $dateValue; // ép kiểu string
        $displayName = ! empty($messageData['from']['display_name']) ? (string) $messageData['from']['display_name'] : '';
        $eventName = MessageEventName::tryFrom($data['event_name'] ?? MessageEventName::DEFAULT->value)
            ?? MessageEventName::DEFAULT;
        $content = ! empty($messageData['text']) ? (string) $messageData['text'] : '';
        $stickerId = ! empty($messageData['sticker']) ? (string) $messageData['sticker'] : '';
        $stickerUrl = ! empty($messageData['url']) ? (string) $messageData['url'] : '';
        $photoUrl = ! empty($messageData['photo_url']) ? (string) $messageData['photo_url'] : '';
        $caption = ! empty($messageData['caption']) ? (string) $messageData['caption'] : '';

        return new self(
            chatId: $chatId,
            messageId: $messageId,
            date: $date,
            displayName: $displayName,
            eventName: $eventName,
            content: $content,
            stickerId: $stickerId,
            stickerUrl: $stickerUrl,
            photoUrl: $photoUrl,
            caption: $caption,
        );
    }

    /**
     * Convert the Message object to an associative array.
     *
     * @return array<string, mixed> Array representation of the message.
     */
    public function toArray(): array
    {
        return [
            'chatId'      => $this->chatId,
            'messageId'   => $this->messageId,
            'date'        => $this->date,
            'displayName' => $this->displayName,
            'eventName'   => $this->eventName->value,
            'content'     => $this->content,
            'stickerId'   => $this->stickerId,
            'stickerUrl'  => $this->stickerUrl,
            'photoUrl'    => $this->photoUrl,
            'caption'     => $this->caption,
        ];
    }

    public function getType(): MessageType
    {
        if (! empty($this->content)) {
            return MessageType::TEXT;
        }
        if (! empty($this->stickerId)) {
            return MessageType::STICKER;
        }
        if (! empty($this->photoUrl)) {
            return MessageType::IMAGE;
        }

        return MessageType::DEFAULT;
    }
}
