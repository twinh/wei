<?php

require '../../lib/Widget/Widget.php';

// Get widget container
$widget = widget();

// Get current environment name
$env = $widget->env();

// Load configuration by environment name
$file = 'config/config_' . $env . '.json';

if (is_file($file)) {
    // Load file content and store it in cache
    $config = $widget->cache->getFileContent($file, function($file) {
        return json_decode(file_get_contents($file), true);
    });
    widget($config);
} else {
    echo sprintf('JSON configuration file "%s" not found', $file);
    return;
}

// Output configuration
var_dump($widget->config('widget'));