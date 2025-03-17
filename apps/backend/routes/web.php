<?php

use App\Http\Controllers\Api\SwaggerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Swagger documentation routes
Route::get('/api/documentation', [SwaggerController::class, 'index'])->name('swagger.index');
Route::get('/api/documentation/yaml', [SwaggerController::class, 'yaml'])->name('swagger.yaml');
