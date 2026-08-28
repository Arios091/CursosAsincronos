<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    'allowed_origins' => [
        'https://sgd.unas.edu.pe',
        'https://sistemasdemo.unas.edu.pe',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['Content-Type', 'X-CSRF-TOKEN', 'X-Requested-With', 'Accept', 'Authorization'],

    'exposed_headers' => ['Content-Length', 'Content-Range'],

    'max_age' => 86400,

    'supports_credentials' => true,

];
