<?php

use App\Models\City;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleRegionUser;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Накатываем миграции на чистую тестовую базу перед запуском
uses(RefreshDatabase::class);

test('авторизованный инженер региона может получить данные городов через AJAX', function () {

    // 1. Явно запускаем сидер справочников (роли и типы городов)
    $this->seed(SystemDictionariesSeeder::class);

    // 2. Генерируем тестовое окружение через фабрики
    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $user = User::factory()->create();

    // Находим ID роли инженера, которую создал сидер справочников
    $VTG_Role = Role::where('name', 'Головний інженер')->first();

    // 3. Связываем пользователя с регионом и ролью через вашу модель связей
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $VTG_Role->id,
        'region_id' => $region->id
    ]);

    // 4. Робот авторизуется под созданным пользователем и шлет AJAX-запрос к API
    $response = $this->actingAs($user)
        ->json('GET', route('api.v1.city-data', [
            'region' => $region->id,
            'city' => $city->id
        ]));

    // 5. Проверяем, что права сработали (200 OK) и вернулись нужные массивы данных
    $response->assertStatus(200)
        ->assertJsonStructure([
            'streets',
            'tps'
        ]);
});
