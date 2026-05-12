<?php

return [
    'gateway' => [
        'api_key' => env('GATEWAY_API_KEY', 'change-me-gateway-key'),
    ],

    'auth' => [
        'base_uri' => rtrim(env('AUTH_SERVICE_URL', 'http://auth-service:8000'), '/'),
        'secret' => env('AUTH_SERVICE_SECRET', ''),
    ],

    'weather' => [
        'base_uri' => rtrim(env('WEATHER_SERVICE_URL', 'http://weather-service:8000'), '/'),
        'secret' => env('WEATHER_SERVICE_SECRET', ''),
    ],

    'maps' => [
        'base_uri' => rtrim(env('MAPS_SERVICE_URL', 'http://maps-service:8000'), '/'),
        'secret' => env('MAPS_SERVICE_SECRET', ''),
    ],

    'hotels' => [
        'base_uri' => rtrim(env('HOTELS_SERVICE_URL', 'http://hotels-service:8000'), '/'),
        'secret' => env('HOTELS_SERVICE_SECRET', ''),
    ],

    'payment' => [
        'base_uri' => rtrim(env('PAYMENT_SERVICE_URL', 'http://payment-service:8000'), '/'),
        'secret' => env('PAYMENT_SERVICE_SECRET', ''),
    ],

    'countries' => [
        'base_uri' => rtrim(env('REST_COUNTRIES_URL', 'https://restcountries.com/v3.1'), '/'),
    ],

    'currency' => [
        'base_uri' => rtrim(env('FRANKFURTER_URL', 'https://api.frankfurter.dev/v1'), '/'),
    ],

    'travel_guide' => [
        'base_uri' => rtrim(env('WIKIPEDIA_REST_URL', 'https://en.wikipedia.org/api/rest_v1'), '/'),
    ],
];
