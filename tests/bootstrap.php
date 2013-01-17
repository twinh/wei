<?php

use Widget\Widget;

require dirname(__DIR__) . '/lib/Widget/Widget.php';

return Widget::create(array(
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
                'dir' => dirname(__DIR__) . '/lib/Widget/Validator/Rule',
                'namespace' => 'Widget\Validator\Rule',
                'format' => 'is%s'
            ),
        )
    ),
));
