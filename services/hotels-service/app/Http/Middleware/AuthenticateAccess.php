<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthenticateAccess
{
    public function handle(Request $request, Closure $next)
    {
        $path = trim($request->path(), '/');

        if ($path === '' || $path === 'health' || $request->isMethod('OPTIONS')) {
            return $next($request);
        }

        $acceptedSecrets = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('ACCEPTED_SECRETS', ''))
        )));

        $providedSecret = (string) $request->header('X-Internal-Service-Key', $request->query('service_key', ''));

        foreach ($acceptedSecrets as $secret) {
            if ($providedSecret !== '' && hash_equals($secret, $providedSecret)) {
                return $next($request);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized service request. Provide a valid X-Internal-Service-Key header.',
            'data' => [],
        ], Response::HTTP_UNAUTHORIZED);
    }
}
