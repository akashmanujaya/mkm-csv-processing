<?php

namespace App\Http\Controllers\API;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\ValidationException;
use Exception;

/**
 * Handles authentication-related operations for the API.
 *
 * This controller provides endpoints for user registration, login, and retrieving the authenticated user's data.
 */
class AuthController extends Controller
{
    /**
     * Registers a new user in the system.
     *
     * Validates the request data and creates a new user with a hashed password. Returns a JWT token for the registered user.
     *
     * @param Request $request The incoming registration request.
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException If validation fails.
     */
    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);

            $token = JWTAuth::fromUser($user);

            return ApiResponse::send(['token' => $token], 'Registration successful.');
        } catch (Exception $e) {
            return ApiResponse::error('Registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Authenticates a user and returns a JWT token.
     *
     * Takes user credentials, attempts to authenticate them, and if successful, returns a JWT token.
     *
     * @param Request $request The request containing the email and password.
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');

            if (!$token = auth('api')->attempt($credentials)) {
                return ApiResponse::error('Unauthorized', 401);
            }

            return ApiResponse::send(['token' => $token], 'Login successful.');
        } catch (Exception $e) {
            return ApiResponse::error('Login failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Retrieves the currently authenticated user's data.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            if (!$user = auth()->user()) {
                return ApiResponse::error('User not found', 404);
            }

            return ApiResponse::send(['user' => $user], 'Data retrieved successfully.');
        } catch (Exception $e) {
            return ApiResponse::error('Failed to retrieve user data: ' . $e->getMessage(), 500);
        }
    }
}
