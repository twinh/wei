<?php

// Autoloading for Composer
if (is_file($file = __DIR__ . '/../vendor/autoload.php')) {
    require $file;
// Fallback to the main class
} else {
    require dirname(__DIR__) . '/lib/Wei.php';
}

// Localhost configuration
wei([
    'wei' => [
        // Display all PHP error message
        'inis' => [
            'error_reporting' => -1,
            'display_errors' => true,
            'date.timezone' => 'UTC',
        ],
        // Enable wei debug option
        'debug' => true,
        // Set up autoload for WeiTest namespace
        'autoloadMap' => [
            '\WeiTest' => __DIR__ . '/unit',
        ],
    ],
    'error' => [
        'enableCli' => false,
    ],
    'http' => [
        'url' => 'http://localhost:8000/call.php',
        // Set ip for WeiTest\HttpTest\::testIp
        'ip' => '127.0.0.1',
    ],
    'db' => [
        'driver' => 'mysql',
        'user' => 'root',
        'password' => '123456',
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'app',
        'charset' => 'utf8',
    ],
    'sqlite:db' => [
        'driver' => 'sqlite',
        'path' => ':memory:',
    ],
    'pgsql:db' => [
        'driver' => 'pgsql',
        'user' => 'postgres',
        'password' => '123456',
        'host' => '127.0.0.1',
        'port' => 5432,
        'dbname' => 'postgres',
    ],
    'sqlite:dbCache' => [
        'deps' => [
            'db' => 'sqlite.db',
        ],
    ],
    'pgsql:dbCache' => [
        'deps' => [
            'db' => 'pgsql.db',
        ],
    ],
    'cache' => [
        'driver' => 'fileCache',
    ],
    'bicache' => [
        'providers' => [
            'master' => 'arrayCache',
            'slave' => 'fileCache',
        ],
    ],
    'res' => [
        'unitTest' => true,
    ],
]);

// Load configuration for CI
foreach (['TRAVIS', 'CODESHIP', 'GITHUB_ACTIONS'] as $ci) {
    if (getenv($ci)) {
        wei(__DIR__ . '/config/' . strtolower(strtr($ci, '_', '-')) . '.php');
    }
}

$localConfig = __DIR__ . '/config/local.php';
if (is_file($localConfig)) {
    wei($localConfig);
}
