<?php

return array(
    'mysql.db' => array(
        'driver'    => 'mysql',
        'user'      => getenv('MYSQL_USER'),
        'password'  => getenv('MYSQL_PASSWORD'),
        'dbname'    => 'wei_test'
    ),
    'pgsql.db' => array(
        'driver'    => 'pgsql',
        'user'      => getenv('PG_USER'),
        'password'  => getenv('PG_PASSWORD'),
        'dbname'    => 'wei_test'
    ),
);
