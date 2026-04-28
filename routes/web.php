<?php

use App\Http\Controllers\App\ConnectionPointController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('app.dashboard');
    })->name('dashboard');

    Route::prefix('/region')->middleware('userHasPermission')->group(function () {

        Route::get('/{region}', [ConnectionPointController::class, 'index'])->name
        ('app.index');

        Route::prefix('/{region}/connections-points')->middleware('edit')->group(function () {
            Route::get('/create', [ConnectionPointController::class, 'create'])->name('connection_point.create');
            Route::put('/store', [ConnectionPointController::class, 'store'])->name('connection_point.store');
            Route::get('/show/{cp}', [ConnectionPointController::class, 'show'])->name('connection_point.show')->withoutMiddleware('edit');
            Route::get('/edit/{cp}', [ConnectionPointController::class, 'edit'])->name('connection_point.edit');
            Route::patch('/update/{cp}', [ConnectionPointController::class, 'update'])->name('connection_point.update');
        });

    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
