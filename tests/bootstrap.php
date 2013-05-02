<?php

use Widget\Widget;

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

require dirname(__DIR__) . '/lib/Widget/Widget.php';

return Widget::create(array(
    'debug' => true,
    'widget' => array(
        'inis' => array(
            // Display all error message
            'error_reporting' => -1,
        ),
        // Set up autoload for WidgetTest namespace
        'autoloadMap' => array(
            'WidgetTest' => __DIR__
        ),
        'alias' => array(
            'mysqlCache' => 'Widget\DbCache'
        )
    ),
    // Databse Widget Configuration
    'db' => array(
        'driver' => 'pdo_sqlite',
        'path' => 'test.sqlite'
    ),
    'mysqlCache' => array(
        'deps' => array(
            'db' => 'db.mysqlCache'
        )
    ),
    'db.mysqlCache' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => 'root',
        'password'  => '123456',
        'dbname'    => 'widget',
        'charset'   => 'utf8'
    ),
    // Doctrine ORM Widget Configuration
    'entityManager' => array(
        'config' => array(
            'proxyDir' => './',
            'annotationDriverPaths' => array('./')
        )
    )
));