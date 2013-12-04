<?php

require '../../lib/Wei/Wei.php';

// Get wei container
$wei = wei();

// Get current environment name
$env = $wei->env();

// Load configuration by environment name
$file = 'config/config-' . $env . '.json';

if (is_file($file)) {
    // Load file content and store it in cache
    $config = $wei->cache->getFileContent($file, function($file) {
        return json_decode(file_get_contents($file), true);
    });
    wei($config);
} else {
    echo sprintf('JSON configuration file "%s" not found', $file);
    return;
}

// Output configuration
var_dump($wei->getConfig('wei'));