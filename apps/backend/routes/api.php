<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Health check endpoint - this should be very lightweight and fast
Route::get('/health', function() {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String()
    ]);
});

// Public API routes
Route::get('/status', function() {
    return response()->json([
        'status' => 'success',
        'message' => 'API is running',
        'data' => [
            'version' => '1.0.0',
            'environment' => config('app.env'),
            'server_time' => now()->toIso8601String(),
        ]
    ]);
});

// Documentation route
Route::get('/documentation', function() {
    return view('swagger');
});