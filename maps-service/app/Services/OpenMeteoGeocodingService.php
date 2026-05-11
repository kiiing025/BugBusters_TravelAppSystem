<?php

namespace App\Services;

use Illuminate\Http\Response;

class OpenMeteoGeocodingService
{
    private string $geocodingUrl;

    public function __construct()
    {
        $this->geocodingUrl = config('services.open_meteo.geocoding_url');
    }

    public function geocode(string $city): array
    {
        $url = $this->geocodingUrl . '?' . http_build_query([
            'name' => $city,
            'count' => 1,
            'language' => 'en',
            'format' => 'json',
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

            return $this->error('Geocoding provider request failed: ' . $message, Response::HTTP_BAD_GATEWAY);
        }

        curl_close($curl);

        $decoded = json_decode($rawResponse, true);

        if (!isset($decoded['results'][0])) {
            return $this->error('City not found', Response::HTTP_NOT_FOUND);
        }

        $location = $decoded['results'][0];

        return [
            'status_code' => Response::HTTP_OK,
            'body' => [
                'status' => 'success',
                'message' => 'Location fetched successfully',
                'data' => [
                    'city' => $location['name'] ?? $city,
                    'admin1' => $location['admin1'] ?? null,
                    'country' => $location['country'] ?? null,
                    'latitude' => $location['latitude'] ?? null,
                    'longitude' => $location['longitude'] ?? null,
                    'timezone' => $location['timezone'] ?? null,
                ],
            ],
        ];
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
