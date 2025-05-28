<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
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
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::post('/cart/submit', [CartController::class, 'submit'])->name('cart.submit');

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
    Route::get('/borrow-details/{id}', [BorrowDetailController::class, 'show'])
        ->name('borrow-details.show');
    Route::get('/borrow-details/{id}/edit', [BorrowDetailController::class, 'edit'])
        ->name('borrow-details.edit');
    Route::put('/borrow-details/{id}/update', [BorrowDetailController::class, 'update'])
        ->name('borrow-details.update');
    Route::delete('/borrow-details/{id}', [BorrowDetailController::class, 'destroy'])
        ->name('borrow-details.destroy');

    // Return Request Routes
    Route::get('/return_requests', [ReturnRequestController::class, 'index'])->name('return-requests.index');
    Route::get('/return_requests/create/{borrowRequest}', [ReturnRequestController::class, 'create'])
        ->name('return_requests.create');
    Route::post('/return_requests', [ReturnRequestController::class, 'store'])->name('return_requests.store');
    Route::get('/return_requests/{return_request}', [ReturnRequestController::class, 'show'])
        ->name('return_requests.show');
    Route::get('/return_requests/{return_request}/edit', [ReturnRequestController::class, 'edit'])
        ->name('return_requests.edit');
    Route::put('/return_requests/{return_request}/approve', [ReturnRequestController::class, 'approve'])
        ->name('return-requests.approve');
    Route::put('/return_requests/{return_request}/reject', [ReturnRequestController::class, 'reject'])
        ->name('return-requests.reject');

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
    Route::get('/borrow-requests/export/excel', [BorrowRequestController::class, 'exportExcel'])->name('borrow-requests.exportExcel');
    Route::get('/borrow-requests/export/pdf', [BorrowRequestController::class, 'exportPdf'])->name('borrow-requests.exportPdf');

    // Fallback Route
    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });
});
