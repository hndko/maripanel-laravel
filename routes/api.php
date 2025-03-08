<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/services', [ApiController::class, 'getServices']);
Route::post('/order', [ApiController::class, 'createOrder']);
Route::get('/order/status/{orderId}', [ApiController::class, 'checkOrderStatus']);
Route::get('/profile', [ApiController::class, 'getProfile']);
