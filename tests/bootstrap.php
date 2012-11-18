<?php

use Widget\Widget;

require dirname(__DIR__) . '/lib/Widget/Widget.php';

Widget::create(array(
    'widget' => array(
        'inis' => array(
            'error_reporting' => -1,
        ),
        // Set up autoload for WidgetTest namespace
        'autoloadMap' => array(
            'WidgetTest' => __DIR__
        ),
    ),
));