<?php

declare(strict_types=1);

namespace Hoangkhacphuc\ZaloBot\Enums;

use TekVN\Enum\EnumUtilities;

enum MessageType: string
{
    use EnumUtilities;

    case TEXT = 'text';
    case IMAGE = 'image';
    case STICKER = 'sticker';
    case DEFAULT = 'default';
}
