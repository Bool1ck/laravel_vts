<?php

namespace Tests\Feature\App\Admin;

use App\Models\City;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleRegionUser;
use App\Models\TpType;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('адміністратор регіону може успішно додати нову ТП в місто', function () {
    // 1. ПІДГОТОВКА: Запускаємо системні довідники (ролі, типи ТП)
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $user = User::factory()->create();

    // Беремо дефолтний тип ТП із сидера
    $tpType = TpType::first();

    // Прив'язуємо користувачу роль адміна для цього регіону
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $adminRole->id,
        'region_id' => $region->id
    ]);

    // Імітуємо дані форми створення ТП
    $formData = [
        'name'       => 'ТП-110',
        'tp_type_id' => $tpType->id,
        'city_id'    => $city->id,
    ];

    // 2. ДІЯ: Робот надсилає PUT запис на збереження ТП
    // Роут із вашого web.php: Route::put('/store', [TPController::class, 'store'])->name('admin.tps.store')
    $response = $this->actingAs($user)
        ->put(route('admin.tps.store', ['region' => $region->id]), $formData);

    // 3. ПЕРЕВІРКА: Перенаправлення на сторінку списку ТП міста
    $response->assertStatus(302)
        ->assertRedirect(route('admin.tps.show', ['region' => $region->id, 'city' => $city->id]));

    // Перевіряємо, що ТП реально записалась в базу даних
    $this->assertDatabaseHas('tps', [
        'name'       => 'ТП-110',
        'tp_type_id' => $tpType->id,
        'city_id'    => $city->id,
    ]);
});

test('адміністратор одного регіону не має доступу до перегляду ТП міста іншого регіону', function () {
    $this->seed(SystemDictionariesSeeder::class);

    // Створюємо два РІЗНИХ регіони
    $regionOne = Region::factory()->create();
    $regionTwo = Region::factory()->create();

    // Створюємо місто, яке належить Регіону №2
    $cityInRegionTwo = City::factory()->create(['region_id' => $regionTwo->id]);

    $user = User::factory()->create();

    // Наш користувач є адміном ТІЛЬКИ в Регіоні №1
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $adminRole->id,
        'region_id' => $regionOne->id
    ]);

    // 2. ДІЯ: Адмін намагається переглянути ТП в місті з чужого Регіону 2 через свій Регіон 1
    $response = $this->actingAs($user)
        ->get(route('admin.tps.show', ['region' => $regionOne->id, 'city' => $cityInRegionTwo->id]));

    // 3. ПЕРЕВІРКА: TpPolicy@view повинна повернути статус 403 Forbidden
    $response->assertStatus(403);
});
