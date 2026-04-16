<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function getWeather(Request $request)
    {
        $city = strtolower($request->query('city'));

        if (!$city) {
            return response()->json([
                'status' => 'error',
                'message' => 'City parameter is required',
                'data' => []
            ], 400);
        }

        // Simple city → coordinates mapping
        $cities = [
            'tokyo' => ['lat' => 35.0, 'lng' => 139.0],
            'manila' => ['lat' => 14.6, 'lng' => 120.9],
            'seoul' => ['lat' => 37.5, 'lng' => 127.0],
            'paris' => ['lat' => 48.8, 'lng' => 2.3],
            'cebu' => ['lat' => 10.3, 'lng' => 123.9],
        ];

        if (!isset($cities[$city])) {
            return response()->json([
                'status' => 'error',
                'message' => 'City not supported yet',
                'data' => []
            ], 404);
        }

        $lat = $cities[$city]['lat'];
        $lng = $cities[$city]['lng'];

        $url = "https://api.open-meteo.com/v1/forecast?latitude={$lat}&longitude={$lng}&current_weather=true";

        $response = file_get_contents($url);

        if (!$response) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch weather data',
                'data' => []
            ], 500);
        }

        $data = json_decode($response, true);

        return response()->json([
            'status' => 'success',
            'message' => 'Weather fetched successfully',
            'data' => [
                'city' => ucfirst($city),
                'temperature_celsius' => $data['current_weather']['temperature'],
                'wind_speed' => $data['current_weather']['windspeed'],
                'weather_code' => $data['current_weather']['weathercode'],
                'time' => $data['current_weather']['time']
            ]
        ]);
    }
}