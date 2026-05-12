<?php

namespace App\Services;

class CountryService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.countries.base_uri'));
    }

    public function byName(string $countryName): array
    {
        return $this->request(
            'GET',
            '/name/' . rawurlencode(trim($countryName)),
            [],
            [
                'fullText' => 'true',
                'fields' => 'name,capital,currencies,region,subregion,population,cca2,cca3,flags',
            ]
        );
    }

    public function byCode(string $countryCode): array
    {
        return $this->request(
            'GET',
            '/alpha/' . rawurlencode(strtoupper(trim($countryCode))),
            [],
            [
                'fields' => 'name,capital,currencies,region,subregion,population,cca2,cca3,flags',
            ]
        );
    }

    public function fetch(string $countryName, ?string $countryCode = null): array
    {
        $result = $countryCode ? $this->byCode($countryCode) : $this->byName($countryName);

        if ($result['status_code'] >= 400) {
            return $result;
        }

        $payload = $result['body'];
        $country = isset($payload[0]) && is_array($payload[0]) ? $payload[0] : (is_array($payload) ? $payload : null);

        if (!$country) {
            return $this->errorResponse('Country not found.', 404);
        }

        $currencies = $country['currencies'] ?? [];
        $currencyCode = null;
        $currencyName = null;

        if (!empty($currencies) && is_array($currencies)) {
            $currencyCode = array_key_first($currencies);
            $currencyName = $currencyCode ? ($currencies[$currencyCode]['name'] ?? null) : null;
        }

        return [
            'status_code' => 200,
            'body' => [
                'status' => 'success',
                'message' => 'Country data fetched successfully.',
                'data' => [
                    'name' => $country['name']['common'] ?? $countryName,
                    'official_name' => $country['name']['official'] ?? null,
                    'code' => $country['cca2'] ?? $country['cca3'] ?? $countryCode,
                    'capital' => $country['capital'][0] ?? null,
                    'region' => $country['region'] ?? null,
                    'subregion' => $country['subregion'] ?? null,
                    'population' => $country['population'] ?? null,
                    'currency_code' => $currencyCode,
                    'currency_name' => $currencyName,
                    'flag' => $country['flags']['png'] ?? $country['flags']['svg'] ?? null,
                ],
            ],
        ];
    }
}
