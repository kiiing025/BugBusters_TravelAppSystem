<?php

namespace App\Services;

class HotelsService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.hotels.base_uri'), config('services.hotels.secret'));
    }

    public function all(array $query = []): array
    {
        return $this->request('GET', '/hotels', [], array_filter($query, fn ($value) => $value !== null && $value !== ''));
    }

    public function find(int|string $id): array
    {
        return $this->request('GET', '/hotels/' . urlencode((string) $id));
    }

    public function create(array $payload): array
    {
        return $this->request('POST', '/hotels', $payload);
    }

    public function update(int|string $id, array $payload): array
    {
        return $this->request('PUT', '/hotels/' . urlencode((string) $id), $payload);
    }

    public function delete(int|string $id): array
    {
        return $this->request('DELETE', '/hotels/' . urlencode((string) $id));
    }
}
