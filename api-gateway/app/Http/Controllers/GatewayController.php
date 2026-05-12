<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\CountryService;
use App\Services\CurrencyService;
use App\Services\HotelsService;
use App\Services\MapsService;
use App\Services\PaymentService;
use App\Services\TravelGuideService;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GatewayController extends Controller
{
    public function __construct(
        private AuthService $authService,
        private WeatherService $weatherService,
        private MapsService $mapsService,
        private HotelsService $hotelsService,
        private PaymentService $paymentService,
        private CountryService $countryService,
        private CurrencyService $currencyService,
        private TravelGuideService $travelGuideService
    ) {
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        return $this->serviceResponse($this->authService->register($request->only([
            'full_name',
            'email',
            'password',
        ])));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        return $this->serviceResponse($this->authService->login($request->only([
            'email',
            'password',
        ])));
    }

    public function profile($id)
    {
        return $this->serviceResponse($this->authService->profile($id));
    }

    public function weather(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|max:100',
        ]);

        $city = trim($request->query('city'));
        $location = $this->mapsService->geocode($city);

        $locationData = $this->extractData($location);
        $weather = $this->weatherService->current([
            'city' => $city,
            'latitude' => $locationData['latitude'] ?? null,
            'longitude' => $locationData['longitude'] ?? null,
        ]);

        $statusCode = max(
            $this->normalizeStatusCode($location['status_code']),
            $this->normalizeStatusCode($weather['status_code'])
        );

        return response()->json([
            'status' => $statusCode >= Response::HTTP_BAD_REQUEST ? 'partial_error' : 'success',
            'message' => 'Weather response assembled by the gateway using maps and weather services.',
            'data' => [
                'city' => $city,
                'location' => $locationData,
                'weather' => $this->extractData($weather),
            ],
        ], $statusCode);
    }

    public function geocode(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|max:100',
        ]);

        return $this->serviceResponse($this->mapsService->geocode($request->query('city')));
    }

    public function country(Request $request)
    {
        $this->validate($request, [
            'country' => 'required_without:country_code|string|max:100',
            'country_code' => 'required_without:country|string|max:3',
        ]);

        $countryName = $request->query('country');
        $countryCode = $request->query('country_code');

        return $this->serviceResponse($this->countryService->fetch($countryName ?: $countryCode, $countryCode));
    }

    public function currency(Request $request)
    {
        $this->validate($request, [
            'base' => 'required|string|max:3',
            'quote' => 'required|string|max:3',
            'amount' => 'nullable|numeric|min:0',
        ]);

        return $this->serviceResponse($this->currencyService->convert(
            $request->query('base'),
            $request->query('quote'),
            (float) $request->query('amount', 1)
        ));
    }

    public function travelGuide(Request $request)
    {
        $this->validate($request, [
            'topic' => 'required|string|max:255',
        ]);

        return $this->serviceResponse($this->travelGuideService->summary($request->query('topic')));
    }

    public function travelSearch(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|max:100',
        ]);

        $city = trim($request->query('city'));
        $location = $this->mapsService->geocode($city);
        $locationData = $this->extractData($location);
        $weather = $this->weatherService->current([
            'city' => $city,
            'latitude' => $locationData['latitude'] ?? null,
            'longitude' => $locationData['longitude'] ?? null,
        ]);
        $country = $this->countryService->fetch(
            $locationData['country'] ?? $city,
            $locationData['country_code'] ?? null
        );
        $countryData = $this->extractData($country);
        $currency = $this->currencyService->convert(
            'PHP',
            $countryData['currency_code'] ?? 'USD',
            100
        );
        $guide = $this->travelGuideService->summary($locationData['city'] ?? $city);
        $hotels = $this->hotelsService->all(['city' => $city]);

        $statusCode = max(
            $this->normalizeStatusCode($location['status_code']),
            $this->normalizeStatusCode($weather['status_code']),
            $this->normalizeStatusCode($country['status_code']),
            $this->normalizeStatusCode($currency['status_code']),
            $this->normalizeStatusCode($guide['status_code']),
            $this->normalizeStatusCode($hotels['status_code'])
        );

        return response()->json([
            'status' => $statusCode >= Response::HTTP_BAD_REQUEST ? 'partial_error' : 'success',
            'message' => 'Travel search response built by the gateway from five external APIs plus the hotels service.',
            'data' => [
                'city' => $city,
                'location' => $locationData,
                'weather' => $this->extractData($weather),
                'country' => $this->extractData($country),
                'currency' => $this->extractData($currency),
                'travel_guide' => $this->extractData($guide),
                'hotels' => $this->extractData($hotels),
            ],
        ], $statusCode);
    }

    public function createHotel(Request $request)
    {
        $this->validateHotel($request);

        return $this->serviceResponse($this->hotelsService->create($request->only([
            'hotel_name',
            'city',
            'address',
            'price_per_night',
            'rating',
        ])));
    }

    public function hotels(Request $request)
    {
        return $this->serviceResponse($this->hotelsService->all([
            'city' => $request->query('city'),
        ]));
    }

    public function hotelById($id)
    {
        return $this->serviceResponse($this->hotelsService->find($id));
    }

    public function updateHotel(Request $request, $id)
    {
        $this->validateHotel($request);

        return $this->serviceResponse($this->hotelsService->update($id, $request->only([
            'hotel_name',
            'city',
            'address',
            'price_per_night',
            'rating',
        ])));
    }

    public function deleteHotel($id)
    {
        return $this->serviceResponse($this->hotelsService->delete($id));
    }

    public function createBooking(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'hotel_id' => 'required|integer',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'total_amount' => 'required|numeric',
        ]);

        return $this->serviceResponse($this->paymentService->createBooking($request->only([
            'user_id',
            'hotel_id',
            'check_in',
            'check_out',
            'total_amount',
        ])));
    }

    public function makePayment(Request $request)
    {
        $this->validate($request, [
            'booking_id' => 'required|integer',
            'amount' => 'required|numeric',
        ]);

        return $this->serviceResponse($this->paymentService->makePayment($request->only([
            'booking_id',
            'amount',
        ])));
    }

    public function getBookings()
    {
        return $this->serviceResponse($this->paymentService->bookings());
    }

    public function getBookingById($id)
    {
        return $this->serviceResponse($this->paymentService->booking($id));
    }

    private function serviceResponse(array $serviceResult)
    {
        return response()->json($serviceResult['body'], $serviceResult['status_code']);
    }

    private function extractData(array $serviceResult, array $default = []): array
    {
        $data = $serviceResult['body']['data'] ?? $default;

        return is_array($data) ? $data : $default;
    }

    private function normalizeStatusCode(int $statusCode): int
    {
        return $statusCode >= Response::HTTP_BAD_REQUEST ? $statusCode : Response::HTTP_OK;
    }

    private function validateHotel(Request $request): void
    {
        $this->validate($request, [
            'hotel_name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'price_per_night' => 'required|numeric',
            'rating' => 'required|numeric|min:0|max:5',
        ]);
    }
}
