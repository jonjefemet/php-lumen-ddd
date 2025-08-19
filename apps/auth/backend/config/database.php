<?php

return [
    'default' => env('DATABASE_CONNECTION', 'mongodb'),

    'connections' => [
        'mongodb' => [
            'driver' => 'mongodb',
            'host' => env('AUTH_DATABASE_HOST', 'localhost'),
            'port' => env('AUTH_DATABASE_PORT', 27017),
            'database' => env('AUTH_DATABASE_NAME', 'finger_auth'),
            'username' => env('AUTH_DATABASE_USERNAME', null),
            'password' => env('AUTH_DATABASE_PASSWORD', null),
            'options' => [
                'database' => env('AUTH_DATABASE_AUTHENTICATION_DATABASE', 'admin'),
            ],
        ],
    ],

    'migrations' => 'migrations',
];
