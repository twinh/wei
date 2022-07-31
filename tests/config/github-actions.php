<?php

return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => getenv('DB_PORT'),
        'password' => 'password',
        'dbname' => 'miaoxing',
    ],
    'schema' => [
        'charset' => 'utf8mb4',
        'collate' => 'utf8mb4_unicode_ci',
    ],
    'redis' => [
        'port' => getenv('REDIS_PORT'),
    ],
    'nearCache' => [
        'providers' => [
            'front' => 'arrayCache',
            'back' => 'cache',
        ],
    ],
];
