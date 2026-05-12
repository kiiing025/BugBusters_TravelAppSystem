<?php

namespace App\Services;

class TravelGuideService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.travel_guide.base_uri'));
    }

    public function summary(string $topic): array
    {
        $response = $this->request('GET', '/page/summary/' . rawurlencode(trim($topic)));

        if ($response['status_code'] >= 400) {
            return $response;
        }

        $payload = is_array($response['body']) ? $response['body'] : [];

        if (empty($payload['extract'])) {
            return $this->errorResponse('Travel guide article was not found.', 404);
        }

        return [
            'status_code' => 200,
            'body' => [
                'status' => 'success',
                'message' => 'Travel guide fetched successfully.',
                'data' => [
                    'title' => $payload['title'] ?? $topic,
                    'description' => $payload['description'] ?? null,
                    'summary' => $payload['extract'] ?? null,
                    'page_url' => $payload['content_urls']['desktop']['page'] ?? null,
                    'mobile_url' => $payload['content_urls']['mobile']['page'] ?? null,
                    'thumbnail' => $payload['thumbnail']['source'] ?? null,
                ],
            ],
        ];
    }
}
