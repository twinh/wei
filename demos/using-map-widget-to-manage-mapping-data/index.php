<?php

require '../../lib/Widget/Widget.php';

// Set configuration for map widget
widget(array(
    'map' => array(
        'file' => './config/map.php'
    )
));

// Get widget container
$widget = widget();

// Output `Yes`
echo $widget->map('yesOrNo', '1');

echo '<p>';

print_r($widget->map('yesOrNo'));

echo '<p>';

// Convert mapping data to JSON string
echo $widget->map->toJson('yesOrNo');

echo '<p>';

// Convert mapping data to HTML select options
$html = $widget->map->toOptions('yesOrNo');

// Output as HTML entity for browser
echo $widget->escape->html($html);