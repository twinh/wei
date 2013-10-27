<?php

$rootDir = dirname(__DIR__);

return array(
    'widget' => array(
        'debug' => true,
        'inis' => array(
            'display_errors' => true,
            'error_reporting' => E_ALL ^ E_NOTICE,
            'date.timezone' => 'Asia/Shanghai'
        ),
        'autoloadMap' => array(
            '' => $rootDir . '/app'
        ),
        'import' => array(
            array(
                'dir' => $rootDir . '/app/Service',
                'namespace' => 'Service'
            )
        ),
        'preload' => array(
            'error',
            'env',
        )
    ),
    'app' => array(

    )
);