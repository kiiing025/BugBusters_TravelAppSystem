<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Weather Service is running',
        'data' => [
            'provider' => config('services.open_meteo.forecast_url'),
        ],
    ]);
});

$router->get('/weather', 'WeatherController@getWeather');
