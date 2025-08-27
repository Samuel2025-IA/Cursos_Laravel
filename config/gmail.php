<?php

return [
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'username' => env('GMAIL_USERNAME'),
    'password' => env('GMAIL_PASSWORD'),
    'encryption' => 'tls',
    'from' => [
        'address' => env('GMAIL_FROM_ADDRESS'),
        'name' => env('GMAIL_FROM_NAME', 'Laravel App'),
    ],
];




