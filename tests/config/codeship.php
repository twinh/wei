<?php

return array(
    'mysql.db' => array(
        'driver'    => 'mysql',
        'user'      => getenv('MYSQL_USER'),
        'password'  => getenv('MYSQL_PASSWORD'),
        'dbname'    => 'widget_test'
    ),
    'pgsql.db' => array(
        'driver'    => 'pgsql',
        'user'      => getenv('PG_USER'),
        'password'  => getenv('PG_PASSWORD'),
        'dbname'    => 'widget_test'
    ),
);