<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;

trait JsonResponseTrait
{
    public function sendJsonResponse(bool $state, string $message, array $errors = [], array $data = [], int $status): JsonResponse
    {
        return response()->json([
            'success' => $state,
            'message' => $message,
            'errors' => $errors,
            'data' => $data
        ], $status);
    }
}
