<?php

return [
    'driver' => env('DB_DRIVER', 'mysql'), // This should match Doctrine driver names

    'drivers' => [
        'sqlite' => [
            'engine'     => 'sqlite',
            'path'       => env('DB_SQLITE', storage_path('database.sqlite')), // Fixed path
            'FETCH_MODE' => PDO::FETCH_OBJ,
            'ERRMODE'    => PDO::ATTR_ERRMODE,
            'EXCEPTION'  => PDO::ERRMODE_EXCEPTION,
        ],
        'mysql' => [
            'engine'     => 'mysql',
            'host'       => env('DB_HOST', '127.0.0.1'),
            'database'   => env('DB_DATABASE', 'framework'),
            'username'   => env('DB_USERNAME', 'root'),
            'password'   => env('DB_PASSWORD', ''),
            'port'       => env('DB_PORT', 3306),
            'charset'    => 'utf8mb4',
            'FETCH_MODE' => PDO::FETCH_OBJ,
            'ERRMODE'    => PDO::ATTR_ERRMODE,
            'EXCEPTION'  => PDO::ERRMODE_EXCEPTION,
            'SSL_CA'     => env('MYSQL_ATTR_SSL_CA', null),
        ],
        'pgsql' => [
            'engine'     => 'pgsql',
            'host'       => env('DB_HOST', '127.0.0.1'),
            'database'   => env('DB_DATABASE', 'framework'),
            'username'   => env('DB_USERNAME', 'root'),
            'password'   => env('DB_PASSWORD', ''),
            'port'       => env('DB_PORT', 5432),
            'charset'    => 'utf8',
            'FETCH_MODE' => PDO::FETCH_OBJ,
            'ERRMODE'    => PDO::ATTR_ERRMODE,
            'EXCEPTION'  => PDO::ERRMODE_EXCEPTION,
        ],
    ]
];