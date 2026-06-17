<?php

declare(strict_types=1);

namespace Tests\Feature\App\Admin;

use App\Models\City;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoleRegionUser;
use App\Models\Street;
use App\Models\StreetType;
use App\Models\User;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('адміністратор регіону може успішно додати нову вулицю в місто', function () {
    // 1. ПОДГОТОВКА
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $user = User::factory()->create();

    $streetType = StreetType::first();

    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $adminRole->id,
        'region_id' => $region->id,
    ]);

    $formData = [
        'name' => 'Шевченка',
        'street_type_id' => $streetType->id,
        'city_id' => $city->id,
    ];

    // 2. ДЕЙСТВИЕ
    $response = $this->actingAs($user)
        ->put(route('admin.streets.store', ['region' => $region->id]), $formData);

    // 3. ПРОВЕРКА
    $response->assertStatus(302)
        ->assertRedirect(route('admin.streets.show', ['region' => $region->id, 'city' => $city->id]));

    $this->assertDatabaseHas('streets', [
        'name' => 'Шевченка',
        'street_type_id' => $streetType->id,
        'city_id' => $city->id,
    ]);
});

test('адміністратор одного регіону не має доступу до перегляду вулиць міста іншого регіону', function () {
    $this->seed(SystemDictionariesSeeder::class);

    // Створюємо два РІЗНИХ регіони
    $regionOne = Region::factory()->create();
    $regionTwo = Region::factory()->create();

    // Створюємо місто, яке чесно належить Регіону №2
    $cityInRegionTwo = City::factory()->create(['region_id' => $regionTwo->id]);

    $user = User::factory()->create();

    // Наш користувач є адміном ТІЛЬКИ в Регіоні №1
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $adminRole->id,
        'region_id' => $regionOne->id,
    ]);

    // 2. ДЕЙСТВИЕ: Адмін намагається обдурити систему. Он запрашивает роут СВОЕГО Региона 1,
    // але підсовує туди ID міста з чужого Регіону 2!
    $response = $this->actingAs($user)
        ->get(route('admin.streets.show', ['region' => $regionOne->id, 'city' => $cityInRegionTwo->id]));

    // 3. ПРОВЕРКА: Тепер Laravel знайде обидві моделі, передасть у StreetPolicy@view,
    // і ваша умова `$city->region_id === $region->id` заблокує хакера з кодом 403!
    $response->assertStatus(403);
});

test('система блокує створення вулиці з ім\'ям, що дублюється, в одному місті', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $user = User::factory()->create();
    $streetType = StreetType::first();

    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $adminRole->id,
        'region_id' => $region->id,
    ]);

    // Створюємо ОДНУ вулицю в базі через фабрику
    Street::factory()->create([
        'name' => 'Грушевського',
        'street_type_id' => $streetType->id,
        'city_id' => $city->id,
    ]);

    // Намагаємось надіслати форму-дублікат з таким самим ім'ям
    $invalidFormData = [
        'name' => 'Грушевського',
        'street_type_id' => $streetType->id,
        'city_id' => $city->id,
    ];

    $response = $this->actingAs($user)
        ->put(route('admin.streets.store', ['region' => $region->id]), $invalidFormData);

    // Перевірка: система повертає назад на форму та складає помилку валідації в сесію
    $response->assertStatus(302);
    $response->assertSessionHasErrors(['name']);
});

test('адміністратор регіону може успішно видалити вулицю', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $user = User::factory()->create();

    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $adminRole->id,
        'region_id' => $region->id,
    ]);

    // Створюємо вулицю, яку будемо видаляти
    $street = Street::factory()->create(['city_id' => $city->id]);

    // ДІЯ: надсилаємо DELETE запрос
    $response = $this->actingAs($user)
        ->delete(route('admin.streets.destroy', ['region' => $region->id, 'street' => $street->id]));

    // Перевірка: успішний редирект назад на сторінку перегляду вулиць цього міста
    $response->assertStatus(302)
        ->assertRedirect(route('admin.streets.show', ['region' => $region->id, 'city' => $city->id]));

    // Перевіряємо, що запис фізично зник із таблиці СУБД
    $this->assertDatabaseMissing('streets', [
        'id' => $street->id,
    ]);
});
