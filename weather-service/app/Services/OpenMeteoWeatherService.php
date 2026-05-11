<?php

namespace App\Services;

use Illuminate\Http\Response;

class OpenMeteoWeatherService
{
    private string $forecastUrl;

    public function __construct()
    {
        $this->forecastUrl = config('services.open_meteo.forecast_url');
    }

    public function currentWeather(string $city): array
    {
        $normalizedCity = strtolower(trim($city));
        $coordinates = $this->coordinatesForCity($normalizedCity);

        if ($coordinates === null) {
            return $this->error('City not supported yet', Response::HTTP_NOT_FOUND);
        }

        $url = $this->forecastUrl . '?' . http_build_query([
            'latitude' => $coordinates['lat'],
            'longitude' => $coordinates['lng'],
            'current_weather' => 'true',
        ]);

        $rawResponse = @file_get_contents($url);

        if ($rawResponse === false) {
            return $this->error('Failed to fetch weather data', Response::HTTP_BAD_GATEWAY);
        }

        $decoded = json_decode($rawResponse, true);

        if (!isset($decoded['current_weather'])) {
            return $this->error('Weather provider returned an unexpected response', Response::HTTP_BAD_GATEWAY);
        }

        return [
            'status_code' => Response::HTTP_OK,
            'body' => [
                'status' => 'success',
                'message' => 'Weather fetched successfully',
                'data' => [
                    'city' => ucfirst($normalizedCity),
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng'],
                    'temperature_celsius' => $decoded['current_weather']['temperature'] ?? null,
                    'wind_speed' => $decoded['current_weather']['windspeed'] ?? null,
                    'weather_code' => $decoded['current_weather']['weathercode'] ?? null,
                    'time' => $decoded['current_weather']['time'] ?? null,
                ],
            ],
        ];
    }

    private function coordinatesForCity(string $city): ?array
    {
        $cities = [
            'tokyo' => ['lat' => 35.6762, 'lng' => 139.6503],
            'manila' => ['lat' => 14.5995, 'lng' => 120.9842],
            'seoul' => ['lat' => 37.5665, 'lng' => 126.9780],
            'paris' => ['lat' => 48.8566, 'lng' => 2.3522],
            'cebu' => ['lat' => 10.3157, 'lng' => 123.8854],
            'davao' => ['lat' => 7.1907, 'lng' => 125.4553],
        ];

        return $cities[$city] ?? null;
    }

    private function error(string $message, int $statusCode): array
    {
        return [
            'status_code' => $statusCode,
            'body' => [
                'status' => 'error',
                'message' => $message,
                'data' => [],
            ],
        ];
    }
}
