<?php

// Autoloading for Composer
if (is_file($file = __DIR__ . '/../vendor/autoload.php')) {
    require $file;
// Fallback to widget container
} else {
    require dirname(__DIR__) . '/lib/Widget/Widget.php';
}

// Localhost configuration
$widget = widget(array(
    'widget' => array(
        // Display all PHP error message
        'inis' => array(
            'error_reporting' => -1,
            'display_errors' => true,
            'date.timezone' => 'UTC',
        ),
        // Enable widget debug option
        'debug' => true,
        // Set up autoload for WidgetTest namespace
        'autoloadMap' => array(
            'WidgetTest' => __DIR__,
            '' => __DIR__ . '/fallback',
        ),
    ),
    'call' => array(
        'url' => 'http://localhost:8000/call.php',
        // Set ip for WidgetTest\CallTest\::testIp
        'ip' => '127.0.0.1'
    ),
    'db' => array(
        'driver'    => 'sqlite',
        'path'      => ':memory:'
    ),
    'mysql.db' => array(
        'driver'    => 'mysql',
        'user'      => 'root',
        'password'  => '123456',
        'host'      => '127.0.0.1',
        'port'      => 3306,
        'dbname'    => 'widget',
        'charset'   => 'utf8'
    ),
    'pgsql.db' => array(
        'driver'    => 'pgsql',
        'user'      => 'postgres',
        'password'  => '123456',
        'host'      => '127.0.0.1',
        'port'      => 5432,
        'dbname'    => 'postgres'
    ),
    'mysql.dbCache' => array(
        'deps' => array(
            'db' => 'mysql.db'
        )
    ),
    'pgsql.dbCache' => array(
        'deps' => array(
            'db' => 'pgsql.db'
        )
    ),
    'bicache' => array(
        'providers' => array(
            'master' => 'arrayCache',
            'slave' => 'fileCache',
        )
    ),
    'response' => array(
        'unitTest' => true
    )
));

// Load configuration for CI
foreach (array('TRAVIS', 'CODESHIP') as $ci) {
    if (getenv($ci)) {
        widget(__DIR__ . '/config/' . strtolower($ci) . '.php');
    }
}

return $widget;