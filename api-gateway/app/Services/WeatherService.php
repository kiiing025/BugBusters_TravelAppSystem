<?php

namespace App\Services;

class WeatherService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.weather.base_uri'), config('services.weather.secret'));
    }

    public function current(array $query): array
    {
        return $this->request('GET', '/weather', [], array_filter($query, fn ($value) => $value !== null && $value !== ''));
    }
}
