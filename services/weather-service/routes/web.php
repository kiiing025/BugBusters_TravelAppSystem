<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Weather Service is running.',
        'data' => [
            'health' => '/health',
            'routes' => [
                'GET /weather',
            ],
        ]
    ]);
});

$router->get('/health', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Weather Service health check passed.',
        'data' => [
            'provider' => config('services.open_meteo.forecast_url'),
        ],
    ]);
});

$router->get('/weather', 'WeatherController@getWeather');
