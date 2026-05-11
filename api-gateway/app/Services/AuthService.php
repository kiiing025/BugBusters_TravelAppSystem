<?php

namespace App\Services;

class AuthService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.auth.base_uri'), config('services.auth.secret'));
    }

    public function register(array $payload): array
    {
        return $this->request('POST', '/register', $payload);
    }

    public function login(array $payload): array
    {
        return $this->request('POST', '/login', $payload);
    }

    public function profile(int|string $id): array
    {
        return $this->request('GET', '/profile/' . urlencode((string) $id));
    }
}
