<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Auth Service is running.',
        'data' => [
            'health' => '/health',
            'routes' => [
                'POST /register',
                'POST /login',
                'GET /profile/{id}',
            ],
        ]
    ]);
});

$router->get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Auth Service health check passed.',
        'data' => [],
    ]);
});

$router->post('/register', 'AuthController@register');
$router->post('/login', 'AuthController@login');
$router->get('/profile/{id}', 'AuthController@profile');
