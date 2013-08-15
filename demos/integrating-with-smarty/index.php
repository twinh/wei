<?php

require 'vendor/autoload.php';
require '../../lib/Widget/Widget.php';

// Get widget container
$widget = widget();

$widget->setConfig(array(
    'widget' => array(
        'autoloadMap' => array(
            'WidgetExtension' => 'src'
        ),
        'aliases' => array(
            'smarty' => 'WidgetExtension\Smarty'
        )
    ),
    // Set options for WidgetExtension\Smarty class
    'smarty' => array(
        'options' => array(
            'template_dir'      => array(
                './views'
            ),
            'config_dir'        => array(),
            'plugins_dir'       => array(),
            'compile_dir'       => null,
            'cache_dir'         => null,
            'left_delimiter'    => '{',
            'right_delimiter'   => '}',
        )
    )
));

// Get Smarty object
/** @var $smarty \Smarty */
$smarty = $widget->smarty();

// Display template
$smarty->display('index.tpl', array(
    'value' => 'widget'
));