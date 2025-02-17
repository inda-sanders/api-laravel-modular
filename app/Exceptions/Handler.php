<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends Exception
{
    public function render($request, Throwable $exception): JsonResponse
    {
        return response()->json([
            'responseCode' => method_exists($exception, 'getCode') && $exception->getCode() ? $exception->getCode() : 500,
            'message' => $exception->getMessage(),
            'data' => null, // You can customize this to include extra data if needed
        ], $this->getStatusCode($exception));
    }

    private function getStatusCode(Throwable $exception): int
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500; // Default status code
    }
}
