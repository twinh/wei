<?php

return array(
    'widget' => array(
        'inis' => array(
            'date.timezone' => 'UTC',
        )
    ),
    'mysql.db' => array(
        'driver'    => 'mysql',
        'user'      => getenv('MYSQL_USER'),
        'password'  => getenv('MYSQL_PASSWORD'),
        'dbname'    => 'widget_test'
    ),
    'pgsql.db' => array(
        'driver'    => 'pgsql',
        'user'      => getenv('PG_USER'),
        'password'  => getenv('PG_PASSWORD'),
        'dbname'    => 'widget_test'
    ),
    'mysql.dbal' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => getenv('MYSQL_USER'),
        'password'  => getenv('MYSQL_PASSWORD'),
        'dbname'    => 'widget_test',
        'charset'   => 'utf8'
    ),
    'pgsql.dbal' => array(
        'driver'    => 'pdo_pgsql',
        'host'      => '127.0.0.1',
        'port'      => '5432',
        'user'      => getenv('PG_USER'),
        'password'  => getenv('PG_PASSWORD'),
        'dbname'    => 'widget_test',
    ),
);