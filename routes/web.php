<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Reader\PaymentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.dashboard.admin');
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('posts', \App\Http\Controllers\Admin\PostController::class)->only(['index', 'destroy']);
    Route::get('payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::get('permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [\App\Http\Controllers\Admin\PermissionController::class, 'store'])->name('permissions.store');
    Route::post('permissions/assign-role', [\App\Http\Controllers\Admin\PermissionController::class, 'assignToRole'])->name('permissions.assignToRole');
    Route::delete('permissions/{permission}', [\App\Http\Controllers\Admin\PermissionController::class, 'destroy'])->name('permissions.destroy');

    // Roles
    Route::post('roles/store', [\App\Http\Controllers\Admin\PermissionController::class, 'storeRole'])->name('roles.store');
    Route::delete('roles/{role}', [\App\Http\Controllers\Admin\PermissionController::class, 'destroyRole'])->name('roles.destroy');

    // Assign permissions to role
    Route::post('permissions/assign-role', [\App\Http\Controllers\Admin\PermissionController::class, 'assignToRole'])->name('permissions.assignToRole');
});

// Creator Routes
Route::middleware(['auth', 'role:creator'])->prefix('creator')->name('creator.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.dashboard.creator');
    })->name('dashboard');

    Route::resource('posts', \App\Http\Controllers\Creator\PostController::class);
});

// Reader Routes
Route::middleware(['auth', 'role:reader'])->prefix('reader')->name('reader.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.dashboard.reader');
    })->name('dashboard');

    Route::resource('posts', \App\Http\Controllers\Reader\PostController::class)->only(['index', 'show']);

    Route::get('/payment/checkout/{post}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{post}', [PaymentController::class, 'cancel'])->name('payment.cancel');

    Route::post('/sslcommerz/initiate/{post}', [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'initiate'])->name('sslcommerz.initiate');

    // Notifications
    Route::get('/notifications/{id}/read', [\App\Http\Controllers\Reader\NotificationController::class, 'read'])->name('notifications.read');
    Route::get('/notifications/mark-all-read', [\App\Http\Controllers\Reader\NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
});
// SSLCommerz callbacks outside auth
// SSLCommerz callbacks outside auth
Route::prefix('reader')->name('reader.')->group(function () {
    Route::match(['get', 'post'], '/sslcommerz/success', [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'success'])->name('sslcommerz.success');
    Route::match(['get', 'post'], '/sslcommerz/fail',    [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'fail'])->name('sslcommerz.fail');
    Route::match(['get', 'post'], '/sslcommerz/cancel',  [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'cancel'])->name('sslcommerz.cancel');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
