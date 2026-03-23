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
    Route::post('/sslcommerz/success', [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'success'])->name('sslcommerz.success');
    Route::post('/sslcommerz/fail', [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'fail'])->name('sslcommerz.fail');
    Route::post('/sslcommerz/cancel', [\App\Http\Controllers\Reader\SSLCommerzPaymentController::class, 'cancel'])->name('sslcommerz.cancel');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
