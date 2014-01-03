<?php

// Autoloading for Composer
if (is_file($file = __DIR__ . '/../vendor/autoload.php')) {
    require $file;
// Fallback to the main class
} else {
    require dirname(__DIR__) . '/lib/Wei.php';
}

// Localhost configuration
wei(array(
    'wei' => array(
        // Display all PHP error message
        'inis' => array(
            'error_reporting' => -1,
            'display_errors' => true,
            'date.timezone' => 'UTC',
        ),
        // Enable wei debug option
        'debug' => true,
        // Set up autoload for WeiTest namespace
        'autoloadMap' => array(
            '\WeiTest' => __DIR__ . '/unit',
            '' => __DIR__ . '/fallback',
        ),
    ),
    'call' => array(
        'url' => 'http://localhost:8000/call.php',
        // Set ip for WeiTest\CallTest\::testIp
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
        'dbname'    => 'wei',
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
    'cache' => array(
        'driver' => 'fileCache'
    ),
    'bicache' => array(
        'providers' => array(
            'master' => 'arrayCache',
            'slave' => 'fileCache',
        )
    ),
    'response' => array(
        'unitTest' => true
    ),
));

// Load configuration for CI
foreach (array('TRAVIS', 'CODESHIP') as $ci) {
    if (getenv($ci)) {
        wei(__DIR__ . '/config/' . strtolower($ci) . '.php');
    }
}
