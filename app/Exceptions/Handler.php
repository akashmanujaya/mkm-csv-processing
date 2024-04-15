<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        // Check if it's an API call and force JSON response
        if ($request->is('api/*')) {
            if ($exception instanceof TokenExpiredException) {
                return ApiResponse::error('Token has expired', 401);
            } elseif ($exception instanceof TokenInvalidException) {
                return ApiResponse::error('Token is invalid', 401);
            } elseif ($exception instanceof JWTException) {
                return ApiResponse::error('Token error', 401);
            } elseif ($exception instanceof AuthenticationException) {
                return ApiResponse::error('Authentication error', 401);
            } elseif ($exception instanceof ValidationException) {
                return ApiResponse::error('Validation Error', 422, $exception->errors());
            }
        }

        return parent::render($request, $exception);
    }
}
