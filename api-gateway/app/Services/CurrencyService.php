<?php

namespace App\Services;

class CurrencyService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.currency.base_uri'));
    }

    public function convert(string $baseCurrency, string $quoteCurrency, float $amount = 1.0): array
    {
        $baseCurrency = strtoupper(trim($baseCurrency));
        $quoteCurrency = strtoupper(trim($quoteCurrency));

        $response = $this->request('GET', '/latest', [], [
            'base' => $baseCurrency,
            'symbols' => $quoteCurrency,
        ]);

        if ($response['status_code'] >= 400) {
            return $response;
        }

        $rate = $response['body']['rates'][$quoteCurrency] ?? null;

        if (!is_numeric($rate)) {
            return $this->errorResponse('Currency conversion rate was not returned by the provider.', 502);
        }

        $convertedAmount = round($amount * (float) $rate, 2);

        return [
            'status_code' => 200,
            'body' => [
                'status' => 'success',
                'message' => 'Currency conversion fetched successfully.',
                'data' => [
                    'base_currency' => $baseCurrency,
                    'quote_currency' => $quoteCurrency,
                    'amount' => round($amount, 2),
                    'exchange_rate' => (float) $rate,
                    'converted_amount' => $convertedAmount,
                    'date' => $response['body']['date'] ?? null,
                ],
            ],
        ];
    }
}
