<?php

return [
    'wei' => [
        'providers' => [
            'cache' => 'redis',
        ],
    ],
    'mysql.db' => [
        'host' => '127.0.0.1',
        'port' => getenv('DB_PORT'),
        'password'  => 'password',
        'dbname'    => 'miaoxing',
    ],
    'redis' => [
        'port' => getenv('REDIS_PORT'),
    ],
];
