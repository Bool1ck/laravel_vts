<?php

use App\Http\Controllers\App\admin\CitiesController;
use App\Http\Controllers\App\admin\StreetsController;
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

    Route::prefix('/region/{region}')->middleware('isCanViewRegion')->group(function () {

        Route::get('/', [ConnectionPointController::class, 'index'])->name
        ('connection_point.index');

        Route::prefix('/connections-points')->middleware('isCanEditRegion')->group(function () {
            Route::get('/create', [ConnectionPointController::class, 'create'])->name('connection_point.create');
            Route::put('/store', [ConnectionPointController::class, 'store'])->name('connection_point.store');
            Route::get('/show/{cp}', [ConnectionPointController::class, 'show'])->withoutMiddleware('isCanEditRegion')->name('connection_point.show');
            Route::get('/edit/{cp}', [ConnectionPointController::class, 'edit'])->name('connection_point.edit');
            Route::patch('/update/{cp}', [ConnectionPointController::class, 'update'])->name('connection_point.update');
        });
    });

    Route::prefix('/admin/region/{region}')->middleware('isAdminInRegion')->group(function () {
        Route::prefix('/cities')->group(function () {
            Route::get('/', [CitiesController::class, 'index'])->name('admin.cities.index');
            Route::get('/create', [CitiesController::class, 'create'])->name('admin.cities.create');
            Route::put('/store', [CitiesController::class, 'store'])->name('admin.cities.store');
            Route::get('/edit/{city}', [CitiesController::class, 'edit'])->name('admin.cities.edit');
            Route::patch('/update/{city}', [CitiesController::class, 'update'])->name('admin.cities.update');
            Route::delete('/destroy/{city}', [CitiesController::class, 'destroy'])->name('admin.cities.destroy');
        })->name('admin.cities');

        Route::prefix('/streets')->group(function () {
            Route::get('/', [StreetsController::class, 'index'])->name('admin.streets.index');
            Route::get('/create', [StreetsController::class, 'create'])->name('admin.streets.create');
            Route::put('/store', [StreetsController::class, 'store'])->name('admin.streets.store');
            Route::get('/edit/{street}', [StreetsController::class, 'edit'])->name('admin.streets.edit');
            Route::patch('/update/{street}', [StreetsController::class, 'update'])->name('admin.streets.update');
            Route::delete('/destroy/{street}', [StreetsController::class, 'destroy'])->name('admin.streets.destroy');
        })->name('admin.streets');

    });

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
