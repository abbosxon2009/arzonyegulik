<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AddressController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('profile', [AuthController::class, 'profile']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::prefix('restaurants')->group(function () {
        Route::get('/', [RestaurantController::class, 'index']);
        Route::get('/{id}', [RestaurantController::class, 'show']);
        Route::get('/{id}/foods', [RestaurantController::class, 'foods']);
        Route::post('/', [RestaurantController::class, 'store']);
        Route::put('/{id}', [RestaurantController::class, 'update']);
    });

    Route::prefix('foods')->group(function () {
        Route::get('/', [FoodController::class, 'index']);
        Route::get('/{id}', [FoodController::class, 'show']);
        Route::post('/', [FoodController::class, 'store']);
        Route::put('/{id}', [FoodController::class, 'update']);
    });

    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::post('/{id}/cancel', [OrderController::class, 'cancel']);
    });

    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::post('/', [AddressController::class, 'store']);
        Route::put('/{id}', [AddressController::class, 'update']);
        Route::delete('/{id}', [AddressController::class, 'destroy']);
    });

    Route::prefix('payments')->group(function () {
        Route::post('/click', [PaymentController::class, 'payWithClick']);
        Route::post('/payme', [PaymentController::class, 'payWithPayme']);
        Route::get('/{id}', [PaymentController::class, 'show']);
    });
});

Route::post('/payments/callback', [PaymentController::class, 'callback']);
