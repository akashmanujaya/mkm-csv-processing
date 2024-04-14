<?php

namespace App\Helpers;

class ApiResponse
{
    public static function send($data = null, $message = '', $status = true, $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function error($message = 'Error', $statusCode = 400, $data = null)
    {
        return self::send($data, $message, false, $statusCode);
    }
}
