<?php

return array(

    'profile' => false,

    'fetch' => PDO::FETCH_CLASS,

    'default' => 'mysql',

    'connections' => array(

        'sqlite' => array(
            'driver'   => 'sqlite',
            'database' => 'application',
            'prefix'   => '',
        ),

        'mysql' => array(
            'driver'   => 'mysql',
            'host'     => '127.0.0.1',
            'database' => 'database',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8',
            'prefix'   => '',
        ),

        'pm25v2' => array(
            'driver'   => 'mysql',
            'host'     => '127.0.0.1',
            'database' => 'pm25v2',
            'username' => 'root',
            'password' => 'root',
            'charset'  => 'utf8',
            'prefix'   => '',
        ),

        'pgsql' => array(
            'driver'   => 'pgsql',
            'host'     => '127.0.0.1',
            'database' => 'database',
            'username' => 'root',
            'password' => '',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
        ),

        'sqlsrv' => array(
            'driver'   => 'sqlsrv',
            'host'     => '127.0.0.1',
            'database' => 'database',
            'username' => 'root',
            'password' => '',
            'prefix'   => '',
        ),

    ),

    'redis' => array(

        'default' => array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0
        ),

    ),

);