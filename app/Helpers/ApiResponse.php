<?php 

namespace App\Helpers;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ApiResponse
{
    public static function success(string $message, mixed $data = null, int $responseCode ): JsonResponse
    {
        return response()->json([
            'responseCode' => $responseCode,
            'message' => $message,
            'data'    => $data,
        ], 200);
    }

    public static function error(string $message, mixed $errors = null, int $responseCode): JsonResponse
    {
        return response()->json([
            'responseCode' => $responseCode,
            'message' => $message,
            'data'  => $errors,
        ], 200);
    }

    public static function validationError(ValidationException $exception): JsonResponse
    {
        return self::error("Validation failed", $exception->errors(), 422);
    }
}
