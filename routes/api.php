<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CartApiController;
use App\Http\Controllers\Api\ItemApiController;
use App\Http\Controllers\Api\ItemUnitApiController;
use App\Http\Controllers\Api\BorrowDetailApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\ReturnDetailApiController;
use App\Http\Controllers\Api\BorrowRequestApiController;
use App\Http\Controllers\Api\ReturnRequestApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

    Route::get('/cart', [CartApiController::class, 'index']);
    Route::post('/cart', [CartApiController::class, 'store']);
    Route::put('/cart/{id}/quantity', [CartApiController::class, 'updateQuantity']);
    Route::delete('/cart/{id}', [CartApiController::class, 'destroy']);
    Route::post('/cart/submit', [CartApiController::class, 'submit']);

    Route::get('/borrow-details/{borrowRequestId}', [BorrowDetailApiController::class, 'index']);
    Route::post('/borrow-details', [BorrowDetailApiController::class, 'store']);

    Route::get('/borrow-requests', [BorrowRequestApiController::class, 'index']);
    Route::post('/borrow-requests', [BorrowRequestApiController::class, 'store']);
    Route::get('/borrow-requests/{id}', [BorrowRequestApiController::class, 'show']);

    Route::get('/items', [ItemApiController::class, 'index']);
    Route::get('/items/{id}', [ItemApiController::class, 'show']);

    Route::get('/item-units', [ItemUnitApiController::class, 'index']);
    Route::get('/item-units/{id}', [ItemUnitApiController::class, 'show']);
    Route::get('/items/{itemId}/units', [ItemUnitApiController::class, 'getByItem']);

    Route::get('/return-requests', [ReturnRequestApiController::class, 'index']);
    Route::get('/return-requests/{id}', [ReturnRequestApiController::class, 'show']);
    Route::post('/return-requests', [ReturnRequestApiController::class, 'store']);

    Route::get('/return-details/{returnRequestId}', [ReturnDetailApiController::class, 'index']);
    Route::post('/return-details', [ReturnDetailApiController::class, 'store']);

    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::get('/notifications/{id}', [NotificationApiController::class, 'show']);
    Route::post('/notifications/{id}/mark-as-read', [NotificationApiController::class, 'markAsRead']);
    Route::post('/notifications/mark-all-as-read', [NotificationApiController::class, 'markAllAsRead']);

    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
