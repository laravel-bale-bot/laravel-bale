<?php
return [
    'token' => env('BALE_BOT_TOKEN', null),
    'base_url' => env('BALE_API_BASE', 'https://tapi.bale.ai'),
    'timeout' => env('BALE_TIMEOUT', 10),

    'webhook' => [
        'enabled' => env('BALE_WEBHOOK_ENABLED', true),
        // full url or path. If path, we will prepend app url when setting webhook
        'url' => env('BALE_WEBHOOK_URL', null),
        // route path used by package (relative). e.g. '/bale/webhook/{secret?}'
        'route' => env('BALE_WEBHOOK_ROUTE', '/bale/webhook'),
        // optional secret token to validate incoming requests
        'secret' => env('BALE_WEBHOOK_SECRET', null),
    ],

    // default driver (allows extensibility later)
    'driver' => env('BALE_DRIVER', 'bale'),

    'default_chat_id' => env('BALE_DEFAULT_CHAT_ID', null),
];
