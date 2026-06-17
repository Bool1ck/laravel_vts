<?php

declare(strict_types=1);

namespace Tests\Feature\App;

use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\CustomerType;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoleRegionUser;
use App\Models\Street;
use App\Models\Tp;
use App\Models\User;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('користувач із роллю ВТГ може успішно відкрити сторінку створення нової точки ПДК', function () {
    // 1. ПІДГОТОВКА: Запускаємо системні довідники
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    // Прив'язуємо користувачу роль ВТГ для цього регіону
    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $region->id,
    ]);

    // 2. ДІЯ: Робот намагається зайти на сторінку створення ТДК
    // Роут із web.php: Route::get('/create', [ConnectionPointController::class, 'create'])->name('connection_point.create')
    $response = $this->actingAs($user)
        ->get(route('connection_point.create', ['region' => $region->id]));

    // 3. ПЕРЕВІРКА: Сервер повинен пустити (200 OK)
    $response->assertStatus(200);
});

test('користувач із роллю ВТГ може успішно зберегти нову точку ПДК у базі даних', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $street = Street::factory()->create(['city_id' => $city->id]);
    $tp = Tp::factory()->create(['city_id' => $city->id]);
    $user = User::factory()->create();

    // Прив'язуємо роль ВТГ
    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $region->id,
    ]);

    // Імітуємо заповнення повної форми створення ТДК на фронтенді
    $customerType = CustomerType::first();
    $formData = [
        'region_id' => $region->id,
        'technical_conditions' => 'ТУ-123/26',
        'technical_conditions_date' => '2026-06-01',
        'customer' => 'ПП Новий Заявник',
        'customer_type_id' => $customerType->id,
        'city_id' => $city->id,
        'street_id' => $street->id,
        'build_number' => '45',
        'tp_id' => $tp->id,
        'powerLineType' => '10',
        'power_line' => 'Л-3',
        'pole' => '21',
        'power' => 15,
        'workTypes' => [],
    ];

    // 2. ДІЯ: Надсилаємо форму методом PUT на збереження
    // Роут із web.php: Route::put('/store', [ConnectionPointController::class, 'store'])->name('connection_point.store')
    $response = $this->actingAs($user)
        ->put(route('connection_point.store', ['region' => $region->id]), $formData);

    // 3. ПЕРЕВІРКА: Після успішного збереження система повинна перенаправити на список точок
    $latestCp = ConnectingPoint::first();
    $response->assertStatus(302)
        ->assertRedirect(route('connection_point.show', ['region' => $region->id, 'cp' => $latestCp->id]));

    // Перевіряємо, що запис успішно з'явився у таблиці СУБД
    $this->assertDatabaseHas('connecting_points', [
        'technical_conditions' => 'ТУ-123/26',
        'customer' => 'ПП Новий Заявник',
        'power' => 15,
        'region_id' => $region->id,
    ]);
});

test('система блокує створення ТДК, якщо обов\'язкові поля порожні', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $region->id,
    ]);

    // Відправляємо абсолютно пусту форму
    $invalidData = [];

    // 2. ДІЯ: Робот намагається надіслати пусті дані
    $response = $this->actingAs($user)
        ->put(route('connection_point.store', ['region' => $region->id]), $invalidData);

    // 3. ПЕРЕВІРКА: Наш StoreConnectionPointRequest повинен заблокувати запит
    // Система повертає редирект назад на форму (302) та складає помилки у сесію
    $response->assertStatus(302);
    $response->assertSessionHasErrors([
        'technical_conditions',
        'technical_conditions_date',
        'customer',
    ]);
});
