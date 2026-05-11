<?php

namespace App\Services;

class PaymentService extends BaseApiService
{
    public function __construct()
    {
        parent::__construct(config('services.payment.base_uri'), config('services.payment.secret'));
    }

    public function createBooking(array $payload): array
    {
        return $this->request('POST', '/booking', $payload);
    }

    public function makePayment(array $payload): array
    {
        return $this->request('POST', '/payment', $payload);
    }

    public function bookings(): array
    {
        return $this->request('GET', '/bookings');
    }

    public function booking(int|string $id): array
    {
        return $this->request('GET', '/bookings/' . urlencode((string) $id));
    }
}
