<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OriginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemUnitController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BorrowDetailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\BorrowRequestController;
use App\Http\Controllers\ReturnRequestController;
use App\Http\Controllers\StockMovementController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super-admin'])->group(function () {
    /*
    |----------------------------------------------------------------------
    | Dashboard & Home
    |----------------------------------------------------------------------
    */
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    /*
    |----------------------------------------------------------------------
    | Cart Management
    |----------------------------------------------------------------------
    */
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('cart.index');
        Route::post('/', [CartController::class, 'store'])->name('cart.store');
        Route::patch('/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
        Route::delete('/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::post('/submit', [CartController::class, 'submit'])->name('cart.submit');
    });

    /*
    |----------------------------------------------------------------------
    | Resource Controllers
    |----------------------------------------------------------------------
    */
    Route::resources([
        'categories' => CategoryController::class,
        'warehouses' => WarehouseController::class,
        'items' => ItemController::class,
        'item-units' => ItemUnitController::class,
        'users' => UserController::class,
        'borrow-requests' => BorrowRequestController::class,
        'origins' => OriginController::class
    ]);

    /*
    |----------------------------------------------------------------------
    | Notification Management
    |----------------------------------------------------------------------
    */
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/{id}', [NotificationController::class, 'show'])->name('notifications.show');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAll');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount']);
        Route::get('/create', [NotificationController::class, 'create'])->name('notifications.create');
        Route::post('/send', [NotificationController::class, 'send'])->name('notifications.send');
    });

    /*
    |----------------------------------------------------------------------
    | Borrow Request Management
    |----------------------------------------------------------------------
    */
    Route::prefix('borrow-requests')->group(function () {
        Route::put('/{id}/approve', [BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
        Route::post('/{id}/reject', [BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    });

    /*
    |----------------------------------------------------------------------
    | Borrow Detail Management
    |----------------------------------------------------------------------
    */
    Route::prefix('borrow-details')->group(function () {
        Route::get('/', [BorrowDetailController::class, 'index'])->name('borrow-details.index');
        Route::get('/create/{borrowRequestId}', [BorrowDetailController::class, 'create'])->name('borrow-details.create');
        Route::post('/store', [BorrowDetailController::class, 'store'])->name('borrow-details.store');
        Route::get('/{id}', [BorrowDetailController::class, 'show'])->name('borrow-details.show');
        Route::get('/{id}/edit', [BorrowDetailController::class, 'edit'])->name('borrow-details.edit');
        Route::put('/{id}/update', [BorrowDetailController::class, 'update'])->name('borrow-details.update');
        Route::delete('/{id}', [BorrowDetailController::class, 'destroy'])->name('borrow-details.destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Return Request Management
    |----------------------------------------------------------------------
    */
    Route::prefix('return_requests')->group(function () {
        Route::get('/', [ReturnRequestController::class, 'index'])->name('return-requests.index');
        Route::get('/create/{borrowRequest}', [ReturnRequestController::class, 'create'])->name('return_requests.create');
        Route::post('/', [ReturnRequestController::class, 'store'])->name('return_requests.store');
        Route::get('/{return_request}', [ReturnRequestController::class, 'show'])->name('return_requests.show');
        Route::get('/{return_request}/edit', [ReturnRequestController::class, 'edit'])->name('return_requests.edit');
        Route::put('/{return_request}/approve', [ReturnRequestController::class, 'approve'])->name('return-requests.approve');
        Route::put('/{return_request}/reject', [ReturnRequestController::class, 'reject'])->name('return-requests.reject');
    });

    /*
    |----------------------------------------------------------------------
    | Stock Movement
    |----------------------------------------------------------------------
    */
    Route::prefix('stock-movements')->group(function () {
        Route::get('/', [StockMovementController::class, 'index'])->name('stock_movements.index');
        Route::post('/', [StockMovementController::class, 'store'])->name('stock_movements.store');
    });

    /*
    |----------------------------------------------------------------------
    | Activity Logs
    |----------------------------------------------------------------------
    */
    Route::prefix('activity-logs')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('activity-logs.index');
        Route::get('/export/excel', [ActivityLogController::class, 'exportExcel'])->name('activity-logs.exportExcel');
        Route::get('/export/pdf', [ActivityLogController::class, 'exportPdf'])->name('activity-logs.exportPdf');
    });

    /*
    |----------------------------------------------------------------------
    | Export Routes
    |----------------------------------------------------------------------
    */
    Route::prefix('exports')->group(function () {
        // User Exports
        Route::get('/users/excel', [UserController::class, 'exportExcel'])->name('users.exportExcel');
        Route::get('/users/pdf', [UserController::class, 'exportPdf'])->name('users.exportPdf');

        // Item Exports
        Route::get('/items/excel', [ItemController::class, 'exportExcel'])->name('items.export.excel');
        Route::get('/items/pdf', [ItemController::class, 'exportPdf'])->name('items.export.pdf');

        // Item Unit Exports
        Route::get('/item-units/excel', [ItemUnitController::class, 'exportExcel'])->name('item-units.exportExcel');
        Route::get('/item-units/pdf', [ItemUnitController::class, 'exportPdf'])->name('item-units.exportPdf');

        // Borrow Request Exports
        Route::get('/borrow-requests/excel', [BorrowRequestController::class, 'exportExcel'])->name('borrow-requests.exportExcel');
        Route::get('/borrow-requests/pdf', [BorrowRequestController::class, 'exportPdf'])->name('borrow-requests.exportPdf');

        // Return Request Exports
        Route::get('/return-details/excel', [ReturnRequestController::class, 'exportExcel'])->name('return-requests.exportExcel');
        Route::get('/return-details/pdf', [ReturnRequestController::class, 'exportPdf'])->name('return-requests.exportPdf');
    });

    /*
    |----------------------------------------------------------------------
    | Fallback Route
    |----------------------------------------------------------------------
    */
    Route::fallback(function () {
        return response()->view('errors.404', [], 404);
    });
});
