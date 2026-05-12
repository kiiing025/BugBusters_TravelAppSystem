<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Maps Service is running.',
        'data' => [
            'health' => '/health',
            'routes' => [
                'GET /geocode',
            ],
        ]
    ]);
});

$router->get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Maps Service health check passed.',
        'data' => [],
    ]);
});

$router->get('/geocode', 'MapsController@geocode');
