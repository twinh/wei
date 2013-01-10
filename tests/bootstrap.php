<?php

use Widget\Widget;

require dirname(__DIR__) . '/lib/Widget/Widget.php';

$widget = Widget::create(array(
    'widget' => array(
        'inis' => array(
            'error_reporting' => -1,
        ),
        // Set up autoload for WidgetTest namespace
        'autoloadMap' => array(
            'WidgetTest' => __DIR__
        ),
        // FIXME The order of options
        'alias' => array(

        ),
        'import' => array(
            // Import is widgets
            array(
                'dir' => dirname(__DIR__) . '/lib/Widget/Is/Rule',
                'namespace' => 'Widget\Is\Rule',
                'format' => 'is%s'
            ),
        )
    ),
));