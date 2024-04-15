<?php

namespace App\Helpers;

/**
 * Provides a standardized way to return API responses.
 *
 * This helper class is designed to ensure consistency in the response structure across the application.
 * It handles both successful and error response formats.
 */
class ApiResponse
{
    /**
     * Send a standardized JSON response.
     *
     * This method structures the API response format consistently across the application.
     * It ensures that all responses follow a standardized format which includes status, message, and data fields.
     *
     * @param mixed $data The data to be returned in the response.
     * @param string $message A message describing the response.
     * @param bool $status The status of the response, true for success and false for failure.
     * @param int $statusCode The HTTP status code, default is 200.
     * @return \Illuminate\Http\JsonResponse Returns a JSON formatted response.
     */
    public static function send($data = null, $message = '', $status = true, $statusCode = 200)
    {
        return response()->json([
            'status' => $status,
            'status_code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Send a standardized JSON error response.
     *
     * This method is a wrapper for sending error responses. It calls the 'send' method, 
     * setting the status to false and allowing the passing of an HTTP status code for errors.
     *
     * @param string $message The error message to be displayed in the response.
     * @param int $statusCode The HTTP status code for the error, default is 400.
     * @param mixed $data Optional additional data to include in the error response.
     * @return \Illuminate\Http\JsonResponse Returns a JSON formatted error response.
     */
    public static function error($message = 'Error', $statusCode = 400, $data = null)
    {
        return self::send($data, $message, false, $statusCode);
    }
}
