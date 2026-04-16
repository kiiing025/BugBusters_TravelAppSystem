<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HotelsController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city');

        $query = DB::table('hotels')
            ->select('id', 'hotel_name', 'city', 'address', 'price_per_night', 'rating', 'created_at', 'updated_at');

        if ($city) {
            $query->where('city', 'LIKE', $city);
        }

        $hotels = $query->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotels fetched successfully',
            'data' => $hotels
        ]);
    }

    public function show($id)
    {
        $hotel = DB::table('hotels')
            ->select('id', 'hotel_name', 'city', 'address', 'price_per_night', 'rating', 'created_at', 'updated_at')
            ->where('id', $id)
            ->first();

        if (!$hotel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel not found',
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel fetched successfully',
            'data' => $hotel
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'hotel_name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'price_per_night' => 'required|numeric',
            'rating' => 'required|numeric'
        ]);

        $hotelId = DB::table('hotels')->insertGetId([
            'hotel_name' => $request->input('hotel_name'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'price_per_night' => $request->input('price_per_night'),
            'rating' => $request->input('rating'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $hotel = DB::table('hotels')->where('id', $hotelId)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel created successfully',
            'data' => $hotel
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $hotel = DB::table('hotels')->where('id', $id)->first();

        if (!$hotel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel not found',
                'data' => []
            ], 404);
        }

        $this->validate($request, [
            'hotel_name' => 'required',
            'city' => 'required',
            'address' => 'required',
            'price_per_night' => 'required|numeric',
            'rating' => 'required|numeric'
        ]);

        DB::table('hotels')
            ->where('id', $id)
            ->update([
                'hotel_name' => $request->input('hotel_name'),
                'city' => $request->input('city'),
                'address' => $request->input('address'),
                'price_per_night' => $request->input('price_per_night'),
                'rating' => $request->input('rating'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $updatedHotel = DB::table('hotels')->where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel updated successfully',
            'data' => $updatedHotel
        ]);
    }

    public function destroy($id)
    {
        $hotel = DB::table('hotels')->where('id', $id)->first();

        if (!$hotel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel not found',
                'data' => []
            ], 404);
        }

        DB::table('hotels')->where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel deleted successfully',
            'data' => []
        ]);
    }
}