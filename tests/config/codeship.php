<?php

return [
    'db' => [
        'driver' => 'mysql',
        'user' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'dbname' => 'wei_test',
    ],
    'pgsql:db' => [
        'driver' => 'pgsql',
        'user' => getenv('PG_USER'),
        'password' => getenv('PG_PASSWORD'),
        'dbname' => 'wei_test',
    ],
];
