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
            '' => $rootDir
        ),
        'import' => array(
            array(
                'dir' => $rootDir . '/services',
                'namespace' => 'services'
            )
        ),
        'preload' => array(
            'error',
            'env',
        )
    ),
    'view' => array(
        'dirs' => array(
            'views'
        )
    )
);
