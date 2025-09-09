<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Enums;

use TekVN\Enum\EnumUtilities;

/**
 * Enum ZaloBotMethod
 *
 * Represents the available API methods for interacting with the Zalo Bot platform.
 * Each case corresponds to a specific API action.
 */
enum ZaloBotMethod: string
{
    use EnumUtilities;

    /** Retrieve information about the current bot. **/
    case GET_ME = 'getMe';

    /**
     * Retrieve updates for the bot, such as new messages or events.
     *
     * ⚠️ Note: This method does not work if a Webhook is already set.
     * To use it, first call deleteWebhook to remove any existing Webhook configuration.
     *
     * Recommended usage:
     * - Local development or testing environments.
     * - Not suitable for production (use Webhooks instead to avoid missing events).
     *
     * @see https://bot.zapps.me/docs/apis/getUpdates/
     */
    case GET_UPDATES = 'getUpdates';

    /** Set a webhook URL for receiving incoming events and messages. **/
    case SET_WEBHOOK = 'setWebhook';

    /** Remove the currently set webhook. **/
    case DELETE_WEBHOOK = 'deleteWebhook';

    /** Retrieve information about the currently configured webhook. **/
    case GET_WEBHOOK_INFO = 'getWebhookInfo';

    /** Send a text message to a user. **/
    case SEND_MESSAGE = 'sendMessage';

    /** Send a photo message to a user. **/
    case SEND_PHOTO = 'sendPhoto';

    /** Send a sticker message to a user. **/
    case SEND_STICKER = 'sendSticker';

    /** Send a chat action (e.g., typing indicator) to a user. **/
    case SEND_CHAT_ACTION = 'sendChatAction';
}
