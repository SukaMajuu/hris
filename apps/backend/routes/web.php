<?php

use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

// Health check endpoint for Azure
Route::get('/health', function() {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String()
    ]);
});

// Swagger documentation routes
Route::get('/api/documentation', [SwaggerController::class, 'index'])->name('swagger.index');
Route::get('/api/documentation/yaml', [SwaggerController::class, 'yaml'])->name('swagger.yaml');