<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API Gateway is running',
        'data' => [
            'services' => [
                'auth' => env('AUTH_SERVICE_URL'),
                'weather' => env('WEATHER_SERVICE_URL'),
                'maps' => env('MAPS_SERVICE_URL'),
                'hotels' => env('HOTELS_SERVICE_URL'),
                'payment' => env('PAYMENT_SERVICE_URL')
            ]
        ]
    ]);
});

$router->post('/register', 'GatewayController@register');
$router->post('/login', 'GatewayController@login');
$router->get('/profile/{id}', 'GatewayController@profile');

$router->get('/weather', 'GatewayController@weather');
$router->get('/geocode', 'GatewayController@geocode');

$router->post('/hotels', 'GatewayController@createHotel');
$router->get('/hotels', 'GatewayController@hotels');
$router->get('/hotels/{id}', 'GatewayController@hotelById');
$router->put('/hotels/{id}', 'GatewayController@updateHotel');
$router->delete('/hotels/{id}', 'GatewayController@deleteHotel');

$router->post('/booking', 'GatewayController@createBooking');
$router->post('/payment', 'GatewayController@makePayment');
$router->get('/bookings', 'GatewayController@getBookings');
$router->get('/bookings/{id}', 'GatewayController@getBookingById');