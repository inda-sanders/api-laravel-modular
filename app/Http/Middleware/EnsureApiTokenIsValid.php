<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$guard): Response
    {
        if ($request->hasHeader('Authorization')) {
            $token = $request->bearerToken();

            if ($token) {
                // Attempt to authenticate the user using the token.  This is crucial.
                if (Auth::guard('api')->check()) { // Use 'api' guard. Important!
                    return $next($request); // User is logged in, proceed.
                } else {
                    // Token is present but invalid (e.g., expired, revoked).
                    return response()->json(['responseCode' => 401, 'message' => 'Invalid or expired token.', 'data' => []], 200);
                }
            }
        } else {
            return $next($request);
        }
    }
}
