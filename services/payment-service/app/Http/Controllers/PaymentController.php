<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function createBooking(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'hotel_id' => 'required|integer',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $booking = Booking::create([
            'user_id' => $request->input('user_id'),
            'hotel_id' => $request->input('hotel_id'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'total_amount' => $request->input('total_amount'),
            'status' => 'pending',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully',
            'data' => [
                'booking_id' => $booking->id,
                'booking' => $booking,
            ]
        ], 201);
    }

    public function makePayment(Request $request)
    {
        $this->validate($request, [
            'booking_id' => 'required|integer',
            'amount' => 'required|numeric|min:0'
        ]);

        $booking = Booking::find($request->input('booking_id'));

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found',
                'data' => []
            ], 404);
        }

        $result = DB::transaction(function () use ($booking, $request) {
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $request->input('amount'),
                'payment_status' => 'paid',
            ]);

            $booking->update([
                'status' => 'paid',
            ]);

            return $payment;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Payment processed successfully',
            'data' => [
                'payment_id' => $result->id,
                'booking_id' => $booking->id,
                'payment' => $result,
                'booking' => $booking->fresh(),
            ]
        ], 201);
    }

    public function getBookings()
    {
        $bookings = Booking::with('payment')->orderByDesc('created_at')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Bookings fetched successfully',
            'data' => $bookings
        ]);
    }

    public function getBookingById($id)
    {
        $booking = Booking::with('payment')->find($id);

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Booking fetched successfully',
            'data' => [
                'booking' => $booking,
                'payment' => $booking->payment,
            ]
        ]);
    }
}
