<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GatewayApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $configuredKey = config('services.gateway.api_key');
        $providedKey = $request->header('X-API-KEY') ?: $request->query('api_key');

        if (empty($configuredKey) || !hash_equals((string) $configuredKey, (string) $providedKey)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized gateway request. Provide a valid X-API-KEY header.',
                'data' => [],
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
