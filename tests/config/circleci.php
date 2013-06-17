<?php

return array(
    'mysqlDb' => array(
        'user'      => 'ubuntu',
        'password'  => '',
        'dsn'       => 'mysql:host=127.0.0.1;port=3306;dbname=circle_test;charset=utf8'
    ),
    'pgsqlDb' => array(
        'user'      => 'ubuntu',
        'password'  => '',
        'dsn'       => 'pgsql:host=127.0.0.1;port=5432;dbname=circle_test'
    ),
    'dbal.mysqlCache' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => 'ubuntu',
        'password'  => '',
        'dbname'    => 'circle_test',
        'charset'   => 'utf8'
    ),
    'dbal.pgCache' => array(
        'driver'    => 'pdo_pgsql',
        'host'      => '127.0.0.1',
        'port'      => '5432',
        'user'      => 'ubuntu',
        'password'  => '',
        'dbname'    => 'circle_test',
    ),
);