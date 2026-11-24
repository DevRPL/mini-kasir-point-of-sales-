<?php
use App\Http\Controllers\{AuthController, ProductController, ReportController, ShiftController, TransactionController, UserController};
use App\Http\Middleware\EnsureRole;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'show'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// Admin & Kasir
Route::middleware(['auth', EnsureRole::class . ':admin,kasir'])->group(function () {
    Route::view('/', 'pos');

    Route::prefix('api')->group(function () {
        Route::get('products', [ProductController::class, 'index']);          // kasir: hanya baca
        Route::get('transactions', [TransactionController::class, 'index']);  // kasir: hanya miliknya
        Route::post('transactions', [TransactionController::class, 'store']);
        Route::get('shift', [ShiftController::class, 'current']);
        Route::post('shift/close', [ShiftController::class, 'close']);

        // Khusus Admin
        Route::middleware(EnsureRole::class . ':admin')->group(function () {
            Route::post('products', [ProductController::class, 'store']);
            Route::put('products/{product}', [ProductController::class, 'update']);
            Route::delete('products/{product}', [ProductController::class, 'destroy']);
            Route::get('report', [ReportController::class, 'summary']);
            Route::get('shift/history', [ShiftController::class, 'history']);
            Route::apiResource('users', UserController::class)->except('show');
        });
    });
});
