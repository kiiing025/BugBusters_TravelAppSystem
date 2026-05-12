<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoWeatherService;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    public function __construct(private OpenMeteoWeatherService $weatherService)
    {
    }

    public function getWeather(Request $request)
    {
        $this->validate($request, [
            'city' => 'required_without_all:latitude,longitude|string|max:100',
            'latitude' => 'required_with:longitude|numeric',
            'longitude' => 'required_with:latitude|numeric',
        ]);

        $weather = $this->weatherService->currentWeather([
            'city' => $request->query('city'),
            'latitude' => $request->query('latitude'),
            'longitude' => $request->query('longitude'),
        ]);

        return response()->json($weather['body'], $weather['status_code']);
    }
}
