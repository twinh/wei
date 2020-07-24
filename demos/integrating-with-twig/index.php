<?php

require 'vendor/autoload.php';
require '../../lib/Wei.php';

// Get wei container
$wei = wei();

$wei->setConfig(array(
    'wei' => array(
        'autoloadMap' => array(
            'WeiExtension' => '.'
        ),
        'aliases' => array(
            'twig' => 'WeiExtension\Twig'
        )
    ),
    // Set options for WeiExtension\Twig class
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
$twig = $wei->twig();

$twig->display('index.html.twig', array(
    'value' => 'wei'
));
