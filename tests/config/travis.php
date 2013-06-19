<?php

return array(
    'mysqlDb' => array(
        'user'      => 'travis',
        'password'  => '',
        'dsn'       => 'mysql:host=127.0.0.1;port=3306;dbname=widget_tests;charset=utf8'
    ),
    'pgsqlDb' => array(
        'user'      => 'travis',
        'password'  => '',
        'dsn'       => 'pgsql:host=127.0.0.1;port=5432;dbname=widget_tests'
    ),
    'dbal.mysqlCache' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => 'travis',
        'password'  => '',
        'dbname'    => 'widget_tests',
        'charset'   => 'utf8'
    ),
    'dbal.pgCache' => array(
        'driver'    => 'pdo_pgsql',
        'host'      => '127.0.0.1',
        'port'      => '5432',
        'user'      => 'postgres',
        'password'  => '',
        'dbname'    => 'widget_tests',
    ),
);