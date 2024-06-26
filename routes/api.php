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

Route::post('/customer', [App\Http\Controllers\CustomerController::class, 'save']);

Route::post('/otp', [App\Http\Controllers\CustomerController::class, 'otp']);

Route::post('/upload', [App\Http\Controllers\CustomerController::class, 'upload']);
