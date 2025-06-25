<?php

return [
    'paths' => [
	'api/*',
	'login',
	'logout',
	],
    'allowed_methods' => ['*'],
    'allowed_origins' => ['reservas.artacho.org'], // Cambia esto en producción por el nombre de tu dominio
    'allowed_origins' => [
        'https://artacho.org',
        'https://www.artacho.org',
        'https://dev.artacho.org',
	'https://reservas.artacho.org',
        'capacitor://localhost',
        'http://localhost'
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
