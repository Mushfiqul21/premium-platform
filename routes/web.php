<?php

use App\Http\Controllers\ProfileController;
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
});

// Reader Routes
Route::middleware(['auth', 'role:reader'])->prefix('reader')->name('reader.')->group(function () {
    Route::get('/dashboard', function () {
        return view('backend.dashboard.reader');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
