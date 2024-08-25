<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [\App\Http\Controllers\Api\ApiController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\ApiController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/profile', [\App\Http\Controllers\Api\ApiController::class, 'profile']); 
});