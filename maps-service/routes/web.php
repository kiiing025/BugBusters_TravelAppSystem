<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Maps Service is running',
        'data' => []
    ]);
});

$router->get('/geocode', 'MapsController@geocode');