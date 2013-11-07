<?php

require '../../lib/Wei/Wei.php';

// Create wei container
$wei = wei(array(
    // Set options for wei container
    'wei' => array(
        // Add autoload for `Demo` namespace
        'autoloadMap' => array(
            'Demo' => __DIR__
        ),
        // Set wei alias
        'alias' => array(
            'hello' => '\Demo\Hello'
        )
    )
));

// Call `hello` wei and output `Hello World`
echo $wei->hello();

echo '<p>';

// Output `Hello Wei`
echo $wei->hello('Wei');