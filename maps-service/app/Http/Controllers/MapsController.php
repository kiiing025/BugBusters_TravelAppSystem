<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function geocode(Request $request)
    {
        $city = $request->query('city');

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City parameter is required',
                'data' => []
            ], 400);
        }

        $url = "https://geocoding-api.open-meteo.com/v1/search?name=" . urlencode($city);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        // Disable SSL verification for local development
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            return response()->json([
                'status' => 'error',
                'message' => 'cURL error: ' . $error,
                'data' => []
            ], 500);
        }

        curl_close($ch);

        $data = json_decode($response, true);

        if (!isset($data['results'][0])) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not found',
                'data' => []
            ], 404);
        }

        $location = $data['results'][0];

        return response()->json([
            'status' => 'success',
            'message' => 'Location fetched successfully',
            'data' => [
                'city' => $location['name'],
                'country' => $location['country'],
                'latitude' => $location['latitude'],
                'longitude' => $location['longitude']
            ]
        ]);
    }
}