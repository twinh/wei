<?php

require 'vendor/autoload.php';
require '../../lib/Widget/Widget.php';

// Get widget container
$widget = widget();

$widget->setConfig(array(
    'widget' => array(
        'autoloadMap' => array(
            'WidgetExtension' => '.'
        ),
        'aliases' => array(
            'twig' => 'WidgetExtension\Twig'
        )
    ),
    // Set options for WidgetExtension\Twig class
    'twig' => array(
        'envOptions' => array(
            'debug'                 => false,
            'charset'               => 'UTF-8',
            'base_template_class'   => 'Twig_Template',
            'strict_variables'      => false,
            'autoescape'            => 'html',
            'cache'                 => false,
            'auto_reload'           => null,
            'optimizations'         => -1,
        ),
        'paths' => array(
            './views'
        )
    )
));

// Get Twig environment object
/** @var $twig Twig_Environment */
$twig = $widget->twig();

$twig->display('index.html.twig', array(
    'value' => 'widget'
));