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
            'display_errors' => true
        ),
        // Enable widget debug option
        'debug' => true,
        // Set up autoload for WidgetTest namespace
        'autoloadMap' => array(
            'WidgetTest' => __DIR__,
            '' => __DIR__ . '/fallback',
        ),
        '+deps' => array(
            'mysqlCache' => 'mysql.dbCache',
            'pgsqlCache' => 'pgsql.dbCache'
        )
    ),
    'call' => array(
        'url' => 'http://localhost:8000/call.php',
        'ip' => '127.0.0.1' // set ip for WidgetTest\CallTest\::testIp
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
    // Doctrine DBAL widget configuration
    'dbal' => array(
        'driver' => 'pdo_sqlite',
        'memory' => true
    ),
    'mysql.dbal' => array(
        'driver'    => 'pdo_mysql',
        'host'      => '127.0.0.1',
        'port'      => '3306',
        'user'      => 'root',
        'password'  => '123456',
        'dbname'    => 'widget',
        'charset'   => 'utf8'
    ),
    'pgsql.dbal' => array(
        'driver'    => 'pdo_pgsql',
        'host'      => '127.0.0.1',
        'port'      => '5432',
        'user'      => 'postgres',
        'password'  => '123456',
        'dbname'    => 'postgres',
    ),
    'mysql.dbCache' => array(
        'deps' => array(
            'db' => 'mysql.db'
        )
    ),
    'pgsql.dbCache' => array(
        'deps' => array(
            'db' => 'mysql.db'
        )
    ),
    // Doctrine ORM widget configuration
    'entityManager' => array(
        'config' => array(
            'proxyDir' => './',
            'annotationDriverPaths' => array('./')
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