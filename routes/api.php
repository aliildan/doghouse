<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\DogController;
use App\Objects\ApiResponse;
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



Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::middleware('auth:api')->group(function () {
    Route::controller(DogController::class)->group(function () {
        Route::post('adddog', 'create');
        Route::get('listdogs', 'list');
    });
});

Route::fallback(function () {
    $response = new ApiResponse([], 'Resource not found!', ApiResponse::STATUS_ERROR);
    return response()->json($response->jsonSerialize(), 404);
});