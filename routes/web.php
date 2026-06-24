<?php

declare(strict_types=1);

use App\Http\Controllers\App\admin\CityController;
use App\Http\Controllers\App\admin\root\RegionController;
use App\Http\Controllers\App\admin\StreetController;
use App\Http\Controllers\App\admin\TPController;
use App\Http\Controllers\App\admin\UserController;
use App\Http\Controllers\App\ConnectionPointController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['auth', 'verified', 'NoCache'])->group(function () {
    // Виводить данні по користувачу в розрізі регіонів
    Route::get('/', function () {
        return view('app.dashboard');
    })->name('dashboard');

    // ==========================================
    // (Connecting Points)
    // ==========================================
    Route::prefix('/region/{region}')->group(function () {

        // Список точок (с viewAny в ConnectingPointPolicy)
        Route::get('/connections-points/{filter?}', [ConnectionPointController::class, 'index'])
            ->middleware('can:viewAny,App\Models\ConnectingPoint,region')
            ->name('connection_point.index');

        // Перегляд точки (викликає view в ConnectingPointPolicy)
        Route::get('/connections-points/show/{cp}', [ConnectionPointController::class, 'show'])
            ->middleware('can:view,cp')
            ->name('connection_point.show');

        // Форма створення нової точки (викликає create в ConnectingPointPolicy)
        Route::get('/create', [ConnectionPointController::class, 'create'])
            ->middleware('can:create,App\Models\ConnectingPoint,region')
            ->name('connection_point.create');

        // Збереження нової точки (викликає create в ConnectingPointPolicy)
        Route::put('/store', [ConnectionPointController::class, 'store'])
            ->middleware('can:create,App\Models\ConnectingPoint,region')
            ->name('connection_point.store');

        // Форма зміни точки (викликає update в ConnectingPointPolicy)
        Route::get('/edit/{cp}', [ConnectionPointController::class, 'edit'])
            ->middleware('can:update,cp')
            ->name('connection_point.edit');

        // Збереження зміненної точки (викликає update в ConnectingPointPolicy)
        Route::patch('/update/{cp}', [ConnectionPointController::class, 'update'])
            ->middleware('can:update,cp')
            ->name('connection_point.update');
    });

    // ==========================================
    // СТОРІНКИ АДМІН-ПАНЕЛІ (Довідники)
    // ==========================================

    // --- РЕГІОНИ (Regions) ---
    Route::prefix('root/regions')->group(function () {
        Route::get('/', [RegionController::class, 'index'])
            ->middleware('can:viewAny,App\Models\Region')
            ->name('root.regions.index');
        Route::get('/create', [RegionController::class, 'create'])
            ->middleware('can:create,App\Models\Region')
            ->name('root.regions.create');
        Route::put('/store', [RegionController::class, 'store'])
            ->middleware('can:create,App\Models\Region')
            ->name('root.regions.store');
        Route::get('/edit/{region}', [RegionController::class, 'edit'])
            ->middleware('can:update,region')
            ->name('root.regions.edit');
        Route::patch('/edit/{region}', [RegionController::class, 'update'])
            ->middleware('can:update,region')
            ->name('root.regions.update');
    });

    Route::prefix('/admin/region/{region}')->group(function () {

        // --- МІСТА (City) ---
        Route::prefix('/cities')->group(function () {
            Route::get('/', [CityController::class, 'index'])
                ->middleware('can:view,App\Models\City,region')
                ->name('admin.cities.index');
            Route::get('/create', [CityController::class, 'create'])
                ->middleware('can:create,App\Models\City,region')
                ->name('admin.cities.create');
            Route::put('/store', [CityController::class, 'store'])
                ->middleware('can:create,App\Models\City,region')
                ->name('admin.cities.store');
            Route::get('/edit/{city}', [CityController::class, 'edit'])
                ->middleware('can:update,city')
                ->name('admin.cities.edit');
            Route::patch('/update/{city}', [CityController::class, 'update'])
                ->middleware('can:update,city')
                ->name('admin.cities.update');
            Route::delete('/destroy/{city}', [CityController::class, 'destroy'])
                ->middleware('can:delete,city')
                ->name('admin.cities.destroy');
        });

        // --- ВУЛИЦІ (Street) ---
        Route::prefix('/streets')->group(function () {
            Route::get('/', [StreetController::class, 'index'])
                ->middleware('can:viewAny,App\Models\Street,region')
                ->name('admin.streets.index');
            Route::get('/create/{city?}', [StreetController::class, 'create'])
                ->middleware('can:create,App\Models\Street,region')
                ->name('admin.streets.create');
            Route::put('/store', [StreetController::class, 'store'])
                ->middleware('can:create,App\Models\Street,region')
                ->name('admin.streets.store');
            Route::get('/edit/{street}', [StreetController::class, 'edit'])
                ->middleware('can:update,street')
                ->name('admin.streets.edit');
            Route::get('/show/{city}', [StreetController::class, 'show'])
                ->middleware('can:view,App\Models\Street,region,city')
                ->name('admin.streets.show');
            Route::patch('/update/{street}', [StreetController::class, 'update'])
                ->middleware('can:update,street')
                ->name('admin.streets.update');
            Route::delete('/destroy/{street}', [StreetController::class, 'destroy'])
                ->middleware('can:delete,street')
                ->name('admin.streets.destroy');
        });

        // --- ТРАНСФОРМАТОРИ (TP) ---
        Route::prefix('/tps')->group(function () {
            Route::get('/', [TPController::class, 'index'])
                ->middleware('can:viewAny,App\Models\Tp,region')
                ->name('admin.tps.index');
            Route::get('/create/{city?}', [TPController::class, 'create'])
                ->middleware('can:create,App\Models\Tp,region')
                ->name('admin.tps.create');
            Route::put('/store', [TPController::class, 'store'])
                ->middleware('can:create,App\Models\Tp,region')
                ->name('admin.tps.store');
            Route::get('/edit/{tp}', [TPController::class, 'edit'])
                ->middleware('can:update,tp')
                ->name('admin.tps.edit');
            Route::get('/show/{city}', [TPController::class, 'show'])
                ->middleware('can:view,App\Models\Tp,region,city')
                ->name('admin.tps.show');
            Route::patch('/update/{tp}', [TPController::class, 'update'])
                ->middleware('can:update,tp')
                ->name('admin.tps.update');
            Route::delete('/destroy/{tp}', [TPController::class, 'destroy'])
                ->middleware('can:delete,tp')
                ->name('admin.tps.destroy');
        });

        // --- КОРИСТУВАЧІ (User) ---
        Route::prefix('/users')->group(function () {
            Route::get('/', [UserController::class, 'index'])
                ->middleware('can:viewAny,App\Models\User,region')
                ->name('admin.users.index');
            Route::get('/create', [UserController::class, 'create'])
                ->middleware('can:create,App\Models\User,region')
                ->name('admin.users.create');
            Route::put('/store', [UserController::class, 'store'])
                ->middleware('can:create,App\Models\User,region')
                ->name('admin.users.store');
            Route::get('/edit/{user}', [UserController::class, 'edit'])
                ->name('admin.users.edit');
            Route::patch('/update/{user}', [UserController::class, 'update'])
                ->name('admin.users.update');
            Route::delete('/destroy/{user}', [UserController::class, 'destroy'])
                ->name('admin.users.destroy');
        });
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    //    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
