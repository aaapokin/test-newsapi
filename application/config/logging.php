<?php

return [
    'default' => env('LOG_CHANNEL', 'daily'),
    'channels' => [
        'stdout' => [
            'driver' => 'monolog',
            'handler' => \Monolog\Handler\StreamHandler::class,
            'with' => [
                'stream' => 'php://stdout',
                'level' => \Monolog\Level::Info,
            ],
        ],
    ],
];
