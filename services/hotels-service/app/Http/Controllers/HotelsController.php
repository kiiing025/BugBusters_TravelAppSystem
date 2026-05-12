<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelsController extends Controller
{
    public function index(Request $request)
    {
        $city = $request->query('city');

        $hotels = Hotel::query()
            ->when($city, function ($query) use ($city) {
                $query->where('city', 'like', '%' . $city . '%');
            })
            ->orderByDesc('rating')
            ->orderBy('hotel_name')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotels fetched successfully',
            'data' => $hotels
        ]);
    }

    public function show($id)
    {
        $hotel = Hotel::find($id);

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
            'rating' => 'required|numeric|min:0|max:5'
        ]);

        $hotel = Hotel::create([
            'hotel_name' => $request->input('hotel_name'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'price_per_night' => $request->input('price_per_night'),
            'rating' => $request->input('rating'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel created successfully',
            'data' => $hotel
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $hotel = Hotel::find($id);

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
            'rating' => 'required|numeric|min:0|max:5'
        ]);

        $hotel->update([
            'hotel_name' => $request->input('hotel_name'),
            'city' => $request->input('city'),
            'address' => $request->input('address'),
            'price_per_night' => $request->input('price_per_night'),
            'rating' => $request->input('rating'),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel updated successfully',
            'data' => $hotel->fresh()
        ]);
    }

    public function destroy($id)
    {
        $hotel = Hotel::find($id);

        if (!$hotel) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hotel not found',
                'data' => []
            ], 404);
        }

        $hotel->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Hotel deleted successfully',
            'data' => []
        ]);
    }
}
