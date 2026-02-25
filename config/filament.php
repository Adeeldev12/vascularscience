<?php

use Filament\Auth\Pages\Login;
use Filament\Auth\Pages\Register;

return [
    'auth' => [
        'guard' => env('FILAMENT_AUTH_GUARD', 'web'),
        'pages' => [
            'login' => Login::class,
            'register' => Register::class,
        ],
    ],
];
