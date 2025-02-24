<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class EnsureApiTokenIsValid extends Controller
{
    /**
     * Handle an incoming request.
     * Validate Bearer token by api key
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$allowedApiKeys): Response
    {
        $apiKey = $request->header('x-api-key') ?? null;

        if ($allowedApiKeys && !$apiKey) {
            return response()->json(['responseCode' => 400, 'message' => 'Missing x-api-key', 'data' => []], 200);
        } else if ($allowedApiKeys && $apiKey) {
            $client = Client::where('api_key', $apiKey)->first();

            if (!$client) {
                return response()->json(['responseCode' => 401, 'message' => 'Invalid api key', 'data' => []], 200);
            }
        }



        if (!empty($allowedApiKeys) && !in_array($client->name, $allowedApiKeys)) {
            return response()->json(['responseCode' => 403, 'message' => 'Unauthorized api key', 'data' => []], 200);
        }

        $request->merge(['client' => $client->name, 'client_id' => $client->id]);

        if ($request->hasHeader('Authorization')) {
            $token = $request->bearerToken();
            if ($token) {
                if (!in_array($client->id, Auth::user()->tokens()->get()->pluck('client_id')->toArray())) {
                    return response()->json(['responseCode' => 403, 'message' => 'Unauthorized User', 'data' => []], 200);
                }

                if (Auth::guard('api')->check()) {
                    return $next($request);
                } else {
                    return response()->json(['responseCode' => 401, 'message' => 'Invalid or expired token.', 'data' => []], 200);
                }
            }
        } else {
            return $next($request);
        }
    }
}
