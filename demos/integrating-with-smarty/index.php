<?php

require 'vendor/autoload.php';
require '../../lib/Wei/Wei.php';

// Get wei container
$wei = wei();

$wei->setConfig(array(
    'wei' => array(
        'autoloadMap' => array(
            'WeiExtension' => 'src'
        ),
        'aliases' => array(
            'smarty' => 'WeiExtension\Smarty'
        )
    ),
    // Set options for WeiExtension\Smarty class
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
$smarty = $wei->smarty();

// Display template
$smarty->display('index.tpl', array(
    'value' => 'wei'
));
