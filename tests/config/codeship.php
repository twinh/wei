<?php

return array(
    'mysqlDb' => array(
        'user'      => getenv('MYSQL_USER'),
        'password'  => getenv('MYSQL_PASSWORD'),
        'dsn'       => 'mysql:host=127.0.0.1;port=3306;dbname=widget_test;charset=utf8'
    ),
    'pgsqlDb' => array(
        'user'      => getenv('PG_USER'),
        'password'  => getenv('PG_PASSWORD'),
        'dsn'       => 'pgsql:host=127.0.0.1;port=5432;dbname=widget_test'
    ),
    'dbal.mysqlCache' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => getenv('MYSQL_USER'),
        'password'  => getenv('MYSQL_PASSWORD'),
        'dbname'    => 'widget_test',
        'charset'   => 'utf8'
    ),
    'dbal.pgCache' => array(
        'driver'    => 'pdo_pgsql',
        'host'      => '127.0.0.1',
        'port'      => '5432',
        'user'      => getenv('PG_USER'),
        'password'  => getenv('PG_PASSWORD'),
        'dbname'    => 'widget_test',
    ),
);