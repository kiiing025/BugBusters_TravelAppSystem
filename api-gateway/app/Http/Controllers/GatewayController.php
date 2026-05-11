<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\HotelsService;
use App\Services\MapsService;
use App\Services\PaymentService;
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
        private PaymentService $paymentService
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
            'city' => 'required_without:location|string|max:100',
            'location' => 'required_without:city|string|max:100',
        ]);

        return $this->serviceResponse($this->weatherService->current([
            'city' => $request->query('city', $request->query('location')),
        ]));
    }

    public function geocode(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|max:100',
        ]);

        return $this->serviceResponse($this->mapsService->geocode($request->query('city')));
    }

    public function travelSearch(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|max:100',
        ]);

        $city = $request->query('city');
        $location = $this->mapsService->geocode($city);
        $weather = $this->weatherService->current(['city' => $city]);
        $hotels = $this->hotelsService->all(['city' => $city]);

        $statusCode = max(
            $this->normalizeStatusCode($location['status_code']),
            $this->normalizeStatusCode($weather['status_code']),
            $this->normalizeStatusCode($hotels['status_code'])
        );

        return response()->json([
            'status' => $statusCode >= Response::HTTP_BAD_REQUEST ? 'partial_error' : 'success',
            'message' => 'Travel search response built by the gateway from maps, weather, and hotels services.',
            'data' => [
                'city' => $city,
                'location' => $location['body'],
                'weather' => $weather['body'],
                'hotels' => $hotels['body'],
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
