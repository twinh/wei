<?php

use Symfony\Component\Yaml\Yaml;

if (!is_file('vendor/autoload.php')) {
    echo 'Please run "composer install" in console to install Symfony Yaml Component';
    return;
}

require 'vendor/autoload.php';
require '../../lib/Widget/Widget.php';

// Get widget container
$widget = widget();

// Get current environment name
$env = $widget->env();

// Load configuration by environment name
$file = 'config/config_' . $env . '.yml';

if (is_file($file)) {
    // Load file content and store it in cache
    $config = $widget->cache->getFileContent($file, function($file) {
        return Yaml::parse($file);
    });
    widget($config);
} else {
    echo sprintf('YAML configuration file "%s" not found', $file);
    return;
}

// Output configuration
var_dump($widget->config('widget'));