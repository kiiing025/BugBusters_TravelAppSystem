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
            'city' => 'required|string|max:100',
        ]);

        $weather = $this->weatherService->currentWeather($request->query('city'));

        return response()->json($weather['body'], $weather['status_code']);
    }
}
