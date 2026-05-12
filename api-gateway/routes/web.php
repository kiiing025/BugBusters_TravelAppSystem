<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API Gateway is running as the single secured entry point.',
        'data' => [
            'health' => '/health',
            'security' => 'Protected gateway routes require the X-API-KEY header.',
            'services' => [
                'auth' => config('services.auth.base_uri'),
                'weather' => config('services.weather.base_uri'),
                'maps' => config('services.maps.base_uri'),
                'hotels' => config('services.hotels.base_uri'),
                'payment' => config('services.payment.base_uri'),
            ],
            'external_apis' => [
                'countries' => config('services.countries.base_uri'),
                'currency' => config('services.currency.base_uri'),
                'travel_guide' => config('services.travel_guide.base_uri'),
            ],
        ],
    ]);
});

$router->get('/health', function () use ($router) {
    return response()->json([
        'status' => 'success',
        'message' => 'Gateway health check passed.',
        'data' => [
            'routes' => [
                'GET /',
                'GET /health',
                'POST /register',
                'POST /login',
                'GET /profile/{id}',
                'GET /weather',
                'GET /geocode',
                'GET /country',
                'GET /currency',
                'GET /travel-guide',
                'GET /travel-search',
                'GET /hotels',
                'POST /hotels',
                'GET /hotels/{id}',
                'PUT /hotels/{id}',
                'DELETE /hotels/{id}',
                'POST /booking',
                'POST /payment',
                'GET /bookings',
                'GET /bookings/{id}',
            ],
        ],
    ]);
});

$router->group(['middleware' => 'gateway.api_key'], function () use ($router) {
    $router->post('/register', 'GatewayController@register');
    $router->post('/login', 'GatewayController@login');
    $router->get('/profile/{id}', 'GatewayController@profile');

    $router->get('/weather', 'GatewayController@weather');
    $router->get('/geocode', 'GatewayController@geocode');
    $router->get('/country', 'GatewayController@country');
    $router->get('/currency', 'GatewayController@currency');
    $router->get('/travel-guide', 'GatewayController@travelGuide');
    $router->get('/travel-search', 'GatewayController@travelSearch');

    $router->post('/hotels', 'GatewayController@createHotel');
    $router->get('/hotels', 'GatewayController@hotels');
    $router->get('/hotels/{id}', 'GatewayController@hotelById');
    $router->put('/hotels/{id}', 'GatewayController@updateHotel');
    $router->delete('/hotels/{id}', 'GatewayController@deleteHotel');

    $router->post('/booking', 'GatewayController@createBooking');
    $router->post('/payment', 'GatewayController@makePayment');
    $router->get('/bookings', 'GatewayController@getBookings');
    $router->get('/bookings/{id}', 'GatewayController@getBookingById');
});
