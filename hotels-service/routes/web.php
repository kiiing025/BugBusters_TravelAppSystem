<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Hotels Service is running',
        'data' => []
    ]);
});

$router->post('/hotels', 'HotelsController@store');
$router->get('/hotels', 'HotelsController@index');
$router->get('/hotels/{id}', 'HotelsController@show');
$router->put('/hotels/{id}', 'HotelsController@update');
$router->delete('/hotels/{id}', 'HotelsController@destroy');