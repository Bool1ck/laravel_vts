<?php

use App\Http\Controllers\App\admin\CityController;
use App\Http\Controllers\App\admin\StreetController;
use App\Http\Controllers\App\admin\TPController;
use App\Http\Controllers\App\admin\UserController;
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
    Route::prefix('/region/{region}')->middleware('UserCanViewRegion')->group(function () {
        Route::get('/', [ConnectionPointController::class, 'index'])->name
        ('connection_point.index');
        Route::prefix('/connections-points')->middleware('UserCanEditRegion')->group(function () {
            Route::get('/create', [ConnectionPointController::class, 'create'])->name('connection_point.create');
            Route::put('/store', [ConnectionPointController::class, 'store'])->name('connection_point.store');
            Route::get('/show/{cp}', [ConnectionPointController::class, 'show'])->withoutMiddleware('UserCanEditRegion')->name('connection_point.show');
            Route::get('/edit/{cp}', [ConnectionPointController::class, 'edit'])->name('connection_point.edit');
            Route::patch('/update/{cp}', [ConnectionPointController::class, 'update'])->name('connection_point.update');
        });
    });
    Route::prefix('/admin/region/{region}')->middleware('UserIsAdminInRegion')->group(function () {
        Route::prefix('/cities')->group(function () {
            Route::get('/', [CityController::class, 'index'])->name('admin.cities.index');
            Route::get('/create', [CityController::class, 'create'])->name('admin.cities.create');
            Route::put('/store', [CityController::class, 'store'])->name('admin.cities.store');
            Route::get('/edit/{city}', [CityController::class, 'edit'])->name('admin.cities.edit');
            Route::patch('/update/{city}', [CityController::class, 'update'])->name('admin.cities.update');
            Route::delete('/destroy/{city}', [CityController::class, 'destroy'])->name('admin.cities.destroy');
        })->name('admin.cities');
        Route::prefix('/streets')->group(function () {
            Route::get('/', [StreetController::class, 'index'])->name('admin.streets.index');
            Route::get('/create', [StreetController::class, 'create'])->name('admin.streets.create');
            Route::put('/store', [StreetController::class, 'store'])->name('admin.streets.store');
            Route::get('/edit/{street}', [StreetController::class, 'edit'])->name('admin.streets.edit');
            Route::patch('/update/{street}', [StreetController::class, 'update'])->name('admin.streets.update');
            Route::delete('/destroy/{street}', [StreetController::class, 'destroy'])->name('admin.streets.destroy');
        })->name('admin.streets');
        Route::prefix('/tps')->group(function () {
            Route::get('/', [TPController::class, 'index'])->name('admin.tps.index');
            Route::get('/create', [TPController::class, 'create'])->name('admin.tps.create');
            Route::put('/store', [TPController::class, 'store'])->name('admin.tps.store');
            Route::get('/edit/{tp}', [TPController::class, 'edit'])->name('admin.tps.edit');
            Route::patch('/update/{tp}', [TPController::class, 'update'])->name('admin.tps.update');
            Route::delete('/destroy/{tp}', [TPController::class, 'destroy'])->name('admin.tps.destroy');
        })->name('admin.tps');
        Route::prefix('/users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
            Route::put('/store', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('/edit/{user}', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::patch('/update/{user}', [UserController::class, 'update'])->name('admin.users.update');
            Route::delete('/destroy/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        })->name('admin.users');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
