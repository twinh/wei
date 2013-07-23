<?php

require '../../lib/Widget/Widget.php';

// Create widget container
$widget = widget(array(
    // Set options for widget container
    'widget' => array(
        // Add autoload for `Demo` namespace
        'autoloadMap' => array(
            'Demo' => __DIR__
        ),
        // Set widget alias
        'alias' => array(
            'hello' => '\Demo\Hello'
        )
    )
));

// Call `hello` widget and output `Hello World`
echo $widget->hello();

echo '<p>';

// Output `Hello Widget`
echo $widget->hello('Widget');