<?php

namespace App\Services;

class MapsService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.maps.base_uri'), config('services.maps.secret'));
    }

    public function geocode(string $city): array
    {
        return $this->request('GET', '/geocode', [], ['city' => $city]);
    }
}
