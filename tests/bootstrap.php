<?php

// Autoloading for Composer
if (is_file($file = __DIR__ . '/../vendor/autoload.php')) {
    require $file;
// Fallback to widget manager
} else {
    require dirname(__DIR__) . '/lib/Widget/Widget.php';
}

// Localhost configuration
$widget = widget(array(
    'widget' => array(
        // Display all error message
        'debug' => true,
        // Set up autoload for WidgetTest namespace
        'autoloadMap' => array(
            'WidgetTest' => __DIR__
        ),
        'alias' => array(
            'mysqlCache' => 'Widget\DbCache',
            'pgCache' => 'Widget\DbCache'
        )
    ),
    'call' => array(
        'url' => 'http://php/widget/tests/WidgetTest/Fixtures/call.php',
        'ip' => '127.0.0.1' // set ip for WidgetTest\CallTest\::testIp
    ),
    'db' => array(
        'dsn' => 'sqlite::memory:'
    ),
    // Doctrine DBAL widget configuration
    'dbal' => array(
        'driver' => 'pdo_sqlite',
        'memory' => true
    ),
    'mysqlCache' => array(
        'deps' => array(
            'dbal' => 'dbal.mysqlCache'
        )
    ),
    'pgCache' => array(
        'deps' => array(
            'dbal' => 'dbal.pgCache'
        )
    ),
    'dbal.mysqlCache' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => 'root',
        'password'  => '123456',
        'dbname'    => 'widget',
        'charset'   => 'utf8'
    ),
    'dbal.pgCache' => array(
        'driver'    => 'pdo_pgsql',
        'host'      => '127.0.0.1',
        'port'      => '5432',
        'user'      => 'postgres',
        'password'  => '123456',
        'dbname'    => 'postgres',
    ),
    // Doctrine ORM widget configuration
    'entityManager' => array(
        'config' => array(
            'proxyDir' => './',
            'annotationDriverPaths' => array('./')
        )
    )
));

// Travis configuration
if (getenv('TRAVIS')) {
    widget(__DIR__ . '/config/travis.php');
}

return $widget;