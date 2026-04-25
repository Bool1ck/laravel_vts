<?php

use App\Http\Controllers\App\ConnectionPointController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('/region')->group(function () {
        Route::get('/', function () {
            return view('app.dashboard');
        })->name('dashboard');

        Route::get('/{region}', [ConnectionPointController::class, 'index'])->name
        ('app.index');

        Route::prefix('/{region}/connections-points')->group(function () {
            Route::get('/show/{cp}', [ConnectionPointController::class, 'show'])->name('connection_point.show');
            Route::get('/create', [ConnectionPointController::class, 'create'])->name('connection_point.create');
            Route::put('/store', [ConnectionPointController::class, 'store'])->name('connection_point.store');
        });
    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
