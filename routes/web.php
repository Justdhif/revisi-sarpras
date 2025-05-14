<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemUnitController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BorrowDetailController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\ReturnRequestController;

// Authentication Routes
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Resource Routes
    Route::resource('categories', CategoryController::class);
    Route::resource('warehouses', WarehouseController::class);
    Route::resource('items', ItemController::class);
    Route::resource('item-units', ItemUnitController::class);
    Route::resource('users', UserController::class);

    // Borrow Request Routes
    Route::resource('borrow-requests', BorrowRequestController::class);
    Route::put('/borrow-requests/{id}/approve', [BorrowRequestController::class, 'approve'])
        ->name('borrow-requests.approve');
    Route::post('/borrow-requests/{id}/reject', [BorrowRequestController::class, 'reject'])
        ->name('borrow-requests.reject');

    // Borrow Detail Routes
    Route::get('/borrow-details', [BorrowDetailController::class, 'index'])->name('borrow-details.index');
    Route::get('/borrow-details/create/{borrowRequestId}', [BorrowDetailController::class, 'create'])
        ->name('borrow-details.create');
    Route::post('/borrow-details/store', [BorrowDetailController::class, 'store'])
        ->name('borrow-details.store');

    // Return Request Routes
    Route::get('/return_requests', [ReturnRequestController::class, 'index'])->name('return-requests.index');
    Route::get('/return_requests/create/{borrowRequest}', [ReturnRequestController::class, 'create'])
        ->name('return_requests.create');
    Route::post('/return_requests', [ReturnRequestController::class, 'store'])->name('return_requests.store');
    Route::get('/return_requests/{return_request}', [ReturnRequestController::class, 'show'])
        ->name('return_requests.show');
    Route::get('/return_requests/{return_request}/edit', [ReturnRequestController::class, 'edit'])
        ->name('return_requests.edit');
    Route::post('/return_requests/{return_request}/approve', [ReturnRequestController::class, 'approve'])
        ->name('return_requests.approve');
    Route::post('/return_requests/{return_request}/reject', [ReturnRequestController::class, 'reject'])
        ->name('return_requests.reject');

    // Activity Logs
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // Export Routes
    Route::get('activity-logs/export/excel', [ActivityLogController::class, 'exportExcel'])->name('activity-logs.exportExcel');
    Route::get('activity-logs/export/pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-logs.exportPdf');
    Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('users.exportExcel');
    Route::get('/users/export/pdf', [UserController::class, 'exportPdf'])->name('users.exportPdf');
    Route::get('/items/export/excel', [ItemController::class, 'exportExcel'])->name('items.export.excel');
    Route::get('/items/export/pdf', [ItemController::class, 'exportPdf'])->name('items.export.pdf');
    Route::get('item-units/export/excel', [ItemUnitController::class, 'exportExcel'])->name('item-units.exportExcel');
    Route::get('item-units/export/pdf', [ItemUnitController::class, 'exportPdf'])->name('item-units.exportPdf');

    // Fallback Route
    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });
});
