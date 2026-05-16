<?php

namespace App\Services;

use Illuminate\Http\Response;

abstract class BaseApiService
{
    protected string $baseUri;
    protected string $serviceSecret;

    public function __construct(string $baseUri, string $serviceSecret = '')
    {
        $this->baseUri = rtrim($baseUri, '/');
        $this->serviceSecret = $serviceSecret;
    }

    protected function request(string $method, string $path, array $payload = [], array $query = []): array
    {
        if (empty($this->baseUri)) {
            return $this->errorResponse('Service base URL is not configured.', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $url = $this->baseUri . '/' . ltrim($path, '/');

        if (!empty($query)) {
            $url .= '?' . http_build_query($query);
        }

        $curl = curl_init();
        $headers = [
            'Accept: application/json',
            'Content-Type: application/json',
            'User-Agent: BugBustersTravelApp/1.0',
        ];

        if (!empty($this->serviceSecret)) {
            $headers[] = 'X-Internal-Service-Key: ' . $this->serviceSecret;
        }

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => (int) env('SERVICE_CONNECT_TIMEOUT', 20),
            CURLOPT_TIMEOUT => (int) env('SERVICE_TIMEOUT', 90),
            CURLOPT_CUSTOMREQUEST => strtoupper($method),
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if (!empty($payload)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload));
        }

        $rawResponse = curl_exec($curl);

        if ($rawResponse === false) {
            $message = curl_error($curl);
            curl_close($curl);

            return $this->errorResponse('Remote service request failed: ' . $message, Response::HTTP_BAD_GATEWAY);
        }

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE) ?: Response::HTTP_OK;
        curl_close($curl);

        $decoded = json_decode($rawResponse, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status_code' => $statusCode,
                'body' => [
                    'status' => $statusCode >= 400 ? 'error' : 'success',
                    'message' => 'Service returned a non-JSON response.',
                    'data' => $rawResponse,
                ],
            ];
        }

        return [
            'status_code' => $statusCode,
            'body' => $decoded,
        ];
    }

    protected function errorResponse(string $message, int $statusCode): array
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
