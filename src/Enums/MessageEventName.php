<?php 
declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Enums;

use TekVN\Enum\EnumUtilities;

/**
 * Enum MessageEventName
 *
 * Represents the different types of message events that the Zalo Bot
 * can receive from the Zalo API.
 *
 * Available cases:
 * - TEXT: Triggered when a text message is received.
 * - STICKER: Triggered when a sticker message is received.
 * - IMAGE: Triggered when an image message is received.
 * - DEFAULT: Triggered when an unsupported or unknown message type is received.
 *
 * This enum leverages {@see EnumUtilities} for additional helper methods.
 */
enum MessageEventName: string
{
    use EnumUtilities;

    /** Text message event **/
    case TEXT = 'message.text.received';

    /** Sticker message event **/
    case STICKER = 'message.sticker.received';

    /** Image message event **/
    case IMAGE = 'message.image.received';

    /** Default event for unsupported message types **/
    case DEFAULT = 'message.unsupported.received';
}
