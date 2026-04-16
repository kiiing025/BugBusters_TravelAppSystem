<?php

namespace App\Http\Controllers;

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
            'check_out' => 'required|date',
            'total_amount' => 'required|numeric'
        ]);

        $bookingId = DB::table('bookings')->insertGetId([
            'user_id' => $request->input('user_id'),
            'hotel_id' => $request->input('hotel_id'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'total_amount' => $request->input('total_amount'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking created successfully',
            'data' => [
                'booking_id' => $bookingId
            ]
        ], 201);
    }

    public function makePayment(Request $request)
    {
        $this->validate($request, [
            'booking_id' => 'required|integer',
            'amount' => 'required|numeric'
        ]);

        $booking = DB::table('bookings')
            ->where('id', $request->input('booking_id'))
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found',
                'data' => []
            ], 404);
        }

        $paymentId = DB::table('payments')->insertGetId([
            'booking_id' => $request->input('booking_id'),
            'amount' => $request->input('amount'),
            'payment_status' => 'paid',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        DB::table('bookings')
            ->where('id', $request->input('booking_id'))
            ->update([
                'status' => 'paid',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Payment processed successfully',
            'data' => [
                'payment_id' => $paymentId,
                'booking_id' => $request->input('booking_id')
            ]
        ], 201);
    }

    public function getBookings()
    {
        $bookings = DB::table('bookings')->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Bookings fetched successfully',
            'data' => $bookings
        ]);
    }

    public function getBookingById($id)
    {
        $booking = DB::table('bookings')->where('id', $id)->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Booking not found',
                'data' => []
            ], 404);
        }

        $payment = DB::table('payments')->where('booking_id', $id)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking fetched successfully',
            'data' => [
                'booking' => $booking,
                'payment' => $payment
            ]
        ]);
    }
}