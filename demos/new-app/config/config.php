<?php

$rootDir = dirname(__DIR__);

return array(
    'wei' => array(
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
                'dir' => $rootDir . '/app/Model',
                'namespace' => 'Model'
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