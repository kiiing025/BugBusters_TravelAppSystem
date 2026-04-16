<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Payment Service is running',
        'data' => []
    ]);
});

$router->post('/booking', 'PaymentController@createBooking');
$router->post('/payment', 'PaymentController@makePayment');
$router->get('/bookings', 'PaymentController@getBookings');
$router->get('/bookings/{id}', 'PaymentController@getBookingById');