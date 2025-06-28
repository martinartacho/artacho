<?php

return [
    'paths' => [
        'api/*',
        'login',
        'logout',
        'sanctum/csrf-cookie'
    ],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'https://artacho.org',
        'https://dev.artacho.org',
        'capacitor://localhost',
        'http://localhost',
        'http://localhost:*' // Permite cualquier puerto de desarrollo
    ],
    'allowed_origins_patterns' => [
        '/^http:\/\/localhost:\d+$/' // Regex para puertos locales
    ],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false, // ¡IMPORTANTE! Cambiar a true  // false más simple para autenticación
];

