<?php

use Illuminate\Support\Str;

$sslOptions = array_filter([
    PDO::MYSQL_ATTR_SSL_CA => env('DB_SSL_CA'),
], fn ($value) => $value !== null && $value !== '');

if (env('DB_SSL_VERIFY_SERVER_CERT') !== null) {
    $sslOptions[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = filter_var(
        env('DB_SSL_VERIFY_SERVER_CERT'),
        FILTER_VALIDATE_BOOLEAN
    );
}

return [
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => $sslOptions,
        ],
    ],

    'migrations' => 'migrations',

    'redis' => [
        'client' => env('REDIS_CLIENT', 'phpredis'),
        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'lumen'), '_') . '_database_'),
        ],
    ],
];
