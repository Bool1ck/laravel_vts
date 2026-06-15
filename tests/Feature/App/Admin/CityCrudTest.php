<?php

// ИСПРАВЛЕНО: Прописан точный namespace, соответствующий вашей структуре папок
namespace Tests\Feature\App\Admin;

use App\Models\City;
use App\Models\CityType;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleRegionUser;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Очищаем тестовую базу перед запуском сценария
uses(RefreshDatabase::class);

test('адміністратор регіону може успішно створити нове місто', function () {
    // 1. ПОДГОТОВКА: Наполняем пустую базу ролями и типами городов из сидера
    $this->seed(SystemDictionariesSeeder::class);

    // Генерируем тестовое окружение через фабрики
    $region = Region::factory()->create();
    $user = User::factory()->create();
    $cityType = CityType::first(); // Берем тип города, созданный сидером

    // Находим ID роли 'admin' и связываем пользователя с регионом через вашу пивот-модель
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $adminRole->id,
        'region_id' => $region->id
    ]);

    // Имитируем данные, которые админ заполнил на форме
    $formData = [
        'name'         => 'Нове Місто',
        'city_type_id' => $cityType->id,
        'region_id'    => $region->id,
    ];

    // 2. ДЕЙСТВИЕ: Робот логинится, заходит на маршрут '.store' и отправляет форму методом PUT
    $response = $this->actingAs($user)
        ->put(route('admin.cities.store', ['region' => $region->id]), $formData);

    // 3. ПРОВЕРКА: Система должна вернуть редирект (302) обратно на список городов этого региона
    $response->assertStatus(302)
        ->assertRedirect(route('admin.cities.index', ['region' => $region->id]));

    // Самый главный шаг: робот лезет в БД и проверяет, что город физически записался в таблицу `cities`
    $this->assertDatabaseHas('cities', [
        'name'         => 'Нове Місто',
        'city_type_id' => $cityType->id,
        'region_id'    => $region->id,
    ]);
});

test('система блокує створення міста з ім\'ям, що дублюється, в одному регіоні', function () {
    // 1. ПОДГОТОВКА: Запускаем справочники ролей и типов
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();
    $cityType = CityType::first();

    // Привязываем роль админа региона
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $adminRole->id,
        'region_id' => $region->id
    ]);

    // Создаем в базе ОДИН город с именем 'Малин' через фабрику
    City::factory()->create([
        'name' => 'Малин',
        'city_type_id' => $cityType->id,
        'region_id' => $region->id
    ]);

    // Имитируем, что админ пытается через форму создать ЕЩЕ ОДИН город с точно таким же именем и типом
    $invalidFormData = [
        'name'         => 'Малин', // Дубликат!
        'city_type_id' => $cityType->id,
        'region_id'    => $region->id,
    ];

    // 2. ДЕЙСТВИЕ: Робот пытается отправить форму-дубликат
    $response = $this->actingAs($user)
        ->put(route('admin.cities.store', ['region' => $region->id]), $invalidFormData);

    // 3. ПРОВЕРКА: Система НЕ должна делать редирект на index. Она должна вернуть обратно на форму (302)
    $response->assertStatus(302);

    // Робот проверяет, что в сессии формы появились ошибки валидации для поля 'name'
    $response->assertSessionHasErrors(['name']);
});

use App\Models\Street; // Не забудьте импортировать модель Street вверху файла, если ее там нет

test('адміністратор регіону може видалити порожнє місто', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    // Привязываем роль админа региона
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $adminRole->id,
        'region_id' => $region->id
    ]);

    // Создаем город, который мы будем удалять
    $city = City::factory()->create(['region_id' => $region->id]);

    // ДЕЙСТВИЕ: Робот отправляет запрос DELETE на удаление города
    // Согласно вашему роутингу: Route::delete('/destroy/{city}', [CityController::class, 'destroy'])
    $response = $this->actingAs($user)
        ->delete(route('admin.cities.destroy', ['region' => $region->id, 'city' => $city->id]));

    // ПРОВЕРКА: Редирект на список городов
    $response->assertStatus(302)
        ->assertRedirect(route('admin.cities.index', ['region' => $region->id]));

    // Убеждаемся, что город физически ИСЧЕЗ из базы данных
    $this->assertDatabaseMissing('cities', [
        'id' => $city->id
    ]);
});

test('система забороняє видаляти місто, якщо у ньому є пов\'язані вулиці', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    // Привязываем роль админа региона
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $adminRole->id,
        'region_id' => $region->id
    ]);

    // Создаем город и привязываем к нему ОДНУ улицу через фабрику (или напрямую)
    $city = City::factory()->create(['region_id' => $region->id]);

    // Создаем связь улицы с этим городом
    Street::factory()->create(['city_id' => $city->id]);

    // ДЕЙСТВИЕ: Робот пытается удалить город, в котором есть улица
    $response = $this->actingAs($user)
        ->delete(route('admin.cities.destroy', ['region' => $region->id, 'city' => $city->id]));

    // ПРОВЕРКА: Политика CityPolicy@delete должна вернуть 403 Forbidden
    $response->assertStatus(403);

    // Убеждаемся, что город НЕ исчез и остался в базе данных невредимым
    $this->assertDatabaseHas('cities', [
        'id' => $city->id
    ]);
});
