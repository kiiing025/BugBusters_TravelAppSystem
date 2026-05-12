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

    public function currentWeather(array $input): array
    {
        $city = trim((string) ($input['city'] ?? ''));
        $latitude = $input['latitude'] ?? null;
        $longitude = $input['longitude'] ?? null;
        $coordinates = null;

        if ($latitude === null || $longitude === null) {
            $normalizedCity = strtolower($city);
            $coordinates = $this->coordinatesForCity($normalizedCity);
            $latitude = $coordinates['lat'] ?? null;
            $longitude = $coordinates['lng'] ?? null;
        }

        if ($latitude === null || $longitude === null) {
            return $this->error('Latitude and longitude are required when the city is not in the fallback map.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $url = $this->forecastUrl . '?' . http_build_query([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'current_weather' => 'true',
            'timezone' => 'auto',
        ]);

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $rawResponse = curl_exec($curl);

        if ($rawResponse === false) {
            $message = curl_error($curl);
            curl_close($curl);

            return $this->error('Failed to fetch weather data: ' . $message, Response::HTTP_BAD_GATEWAY);
        }

        curl_close($curl);

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
                    'city' => $city !== '' ? ucwords(strtolower($city)) : null,
                    'latitude' => (float) $latitude,
                    'longitude' => (float) $longitude,
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
