<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GatewayController extends Controller
{
    private function sendRequest($method, $url, $payload = null)
    {
        $ch = curl_init();

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        if ($payload !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            return response()->json([
                'status' => 'error',
                'message' => 'Gateway cURL error: ' . $error,
                'data' => []
            ], 500);
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($response, true);

        if ($decoded === null) {
            return response($response, $httpCode)->header('Content-Type', 'application/json');
        }

        return response()->json($decoded, $httpCode);
    }

    public function register(Request $request)
    {
        return $this->sendRequest('POST', env('AUTH_SERVICE_URL') . '/register', [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);
    }

    public function login(Request $request)
    {
        return $this->sendRequest('POST', env('AUTH_SERVICE_URL') . '/login', [
            'email' => $request->input('email'),
            'password' => $request->input('password')
        ]);
    }

    public function profile($id)
    {
        return $this->sendRequest('GET', env('AUTH_SERVICE_URL') . '/profile/' . $id);
    }

    public function weather(Request $request)
    {
        return $this->sendRequest('GET', env('WEATHER_SERVICE_URL') . '/weather?city=' . urlencode($request->query('city')));
    }

    public function geocode(Request $request)
    {
        return $this->sendRequest('GET', env('MAPS_SERVICE_URL') . '/geocode?city=' . urlencode($request->query('city')));
    }

    public function createHotel(Request $request)
    {
        return $this->sendRequest('POST', env('HOTELS_SERVICE_URL') . '/hotels', [
            'hotel_name' => $request->input('hotel_name'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'price_per_night' => $request->input('price_per_night'),
            'rating' => $request->input('rating')
        ]);
    }

    public function hotels(Request $request)
    {
        $url = env('HOTELS_SERVICE_URL') . '/hotels';

        if ($request->query('city')) {
            $url .= '?city=' . urlencode($request->query('city'));
        }

        return $this->sendRequest('GET', $url);
    }

    public function hotelById($id)
    {
        return $this->sendRequest('GET', env('HOTELS_SERVICE_URL') . '/hotels/' . $id);
    }

    public function updateHotel(Request $request, $id)
    {
        return $this->sendRequest('PUT', env('HOTELS_SERVICE_URL') . '/hotels/' . $id, [
            'hotel_name' => $request->input('hotel_name'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'price_per_night' => $request->input('price_per_night'),
            'rating' => $request->input('rating')
        ]);
    }

    public function deleteHotel($id)
    {
        return $this->sendRequest('DELETE', env('HOTELS_SERVICE_URL') . '/hotels/' . $id);
    }

    public function createBooking(Request $request)
    {
        return $this->sendRequest('POST', env('PAYMENT_SERVICE_URL') . '/booking', [
            'user_id' => $request->input('user_id'),
            'hotel_id' => $request->input('hotel_id'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'total_amount' => $request->input('total_amount')
        ]);
    }

    public function makePayment(Request $request)
    {
        return $this->sendRequest('POST', env('PAYMENT_SERVICE_URL') . '/payment', [
            'booking_id' => $request->input('booking_id'),
            'amount' => $request->input('amount')
        ]);
    }

    public function getBookings()
    {
        return $this->sendRequest('GET', env('PAYMENT_SERVICE_URL') . '/bookings');
    }

    public function getBookingById($id)
    {
        return $this->sendRequest('GET', env('PAYMENT_SERVICE_URL') . '/bookings/' . $id);
    }
}