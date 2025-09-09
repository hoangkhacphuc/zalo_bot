<?php

declare(strict_types=1);

return [
    'access_token'   => env('ZALO_ACCESS_TOKEN', ''),
    'webhook_url'    => env('ZALO_WEBHOOK_URL', ''),
    'webhook_secret' => env('ZALO_WEBHOOK_SECRET', ''),
];
