<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseAPIController extends Controller
{
    public static function jsonResponse(bool $result, $message = null, $data = null, $status = 200)
    {
        return response()->json([
            'success' => $result,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public function successResponse($data = null, $message = null): JsonResponse
    {
        return self::jsonResponse(true, $message, $data);
    }

    public function errorResponse($message = null, int $code=400, $data = null): JsonResponse
    {
        return self::jsonResponse(false, $message ?? 'เกิดข้อผิดพลาดระหว่างดำเนินการ', $data, $code);
    }
}
