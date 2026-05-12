<?php

namespace App\Http\Controllers;

use App\Services\OpenMeteoGeocodingService;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function __construct(private OpenMeteoGeocodingService $geocodingService)
    {
    }

    public function geocode(Request $request)
    {
        $this->validate($request, [
            'city' => 'required|string|max:100',
        ]);

        $location = $this->geocodingService->geocode($request->query('city'));

        return response()->json($location['body'], $location['status_code']);
    }
}
