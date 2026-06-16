<?php

use App\Models\City;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleRegionUser;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Накочуємо міграції на чисту тестову базу перед запуском
uses(RefreshDatabase::class);

test('Перевірка доступу до даних міст([streets, tps]) по API AJAX запиту для ролей користувачів :',
    function (string $roleName) {

        // 1. Явно запускаємо сидер довідників (ролі та типи міст)
    $this->seed(SystemDictionariesSeeder::class);

    // 2. Генерируем тестовое окружение через фабрики
    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $user = User::factory()->create();

    // Находим ID роли инженера, которую создал сидер справочников
    $currentRole  = Role::where('name', $roleName)->first();

    // 3. Связываем пользователя с регионом и ролью через вашу модель связей
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $currentRole->id,
        'region_id' => $region->id
    ]);

    // 4. Робот авторизуется под созданным пользователем и шлет AJAX-запрос к API
    $response = $this->actingAs($user)
        ->json('GET', route('api.v1.city-data', [
            'region' => $region->id,
            'city' => $city->id
        ]));
    $allowedRoles = config('roles.edit_roles');

    if (in_array($roleName, $allowedRoles)) {
        // Если роль в белом списке — проверяем успешный ответ и структуру JSON
        $response->assertStatus(200)
            ->assertJsonStructure(['streets', 'tps']);
    } else {
        // Если роли доступ запрещен — робот проверяет, что сервер вернул 403 Forbidden
        $response->assertStatus(403);
    }
})->with(['admin', 'Головний інженер', 'ВТГ', 'Глядач']);
