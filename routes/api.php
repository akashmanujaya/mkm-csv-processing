<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\CSVController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

// Apply rate limiting to auth routes
Route::middleware('throttle:api')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});


Route::middleware('auth:api', 'throttle:api')->group(function () {
    Route::get('me', [AuthController::class, 'me']);
    Route::post('upload-csv', [CSVController::class, 'upload']);
    Route::get('product/{sku}', [CSVController::class, 'getProductBySku']);
});