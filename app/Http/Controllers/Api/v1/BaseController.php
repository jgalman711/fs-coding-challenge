<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function success(string $message, $data = null, $status = 200): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], $status);
    }

    protected function error(string $message, $status = 500): JsonResponse
    {
        return response()->json([
            'message' => $message,
            'status' => $status,
        ], $status);
    }
}
