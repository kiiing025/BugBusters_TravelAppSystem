<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Weather Service root works',
        'data' => []
    ]);
});

$router->get('/weather', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Weather route works',
        'data' => [
            'city' => 'Tokyo'
        ]
    ]);
});