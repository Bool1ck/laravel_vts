<?php

namespace Tests\Feature\App\Admin;

use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Models\RoleRegionUser;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('адміністратор регіону може успішно створити нового користувача з прив\'язкою ролі', function () {
    // 1. ПІДГОТОВКА: Запускаємо системні довідники ролей
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $userAdmin = User::factory()->create();

    // ИСПРАВЛЕНО: Берем роль 'ВТГ' из вашего реального конфига ролей
    $vtgRole = Role::where('name', 'ВТГ')->first();

    // Робимо нашого першого користувача адміном поточного регіону
    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id'   => $userAdmin->id,
        'role_id'   => $adminRole->id,
        'region_id' => $region->id
    ]);

    // Дані для створення нового користувача (с ролью ВТГ)
    $formData = [
        'name'     => 'Іван ВТГ',
        'email'    => 'ivan@vts.com',
        'role_id'  => $vtgRole->id,
    ];

    // 2. ДІЯ: Адмін відправляє форму створення нового користувача методом PUT
    $response = $this->actingAs($userAdmin)
        ->put(route('admin.users.store', ['region' => $region->id]), $formData);

    // 3. ПЕРЕВІРКА: Перенаправлення на список користувачів
    $response->assertStatus(302)
        ->assertRedirect(route('admin.users.index', ['region' => $region->id]));

    // Перевіряємо, що користувач з'явився в загальній таблиці users
    $this->assertDatabaseHas('users', [
        'name'  => 'Іван ВТГ',
        'email' => 'ivan@vts.com',
    ]);

    // Перевіряємо роботу нашого UserService: чи створився зв'язок у role_region_users?
    $newUser = User::where('email', 'ivan@vts.com')->first();
    $this->assertDatabaseHas('role_region_users', [
        'user_id'   => $newUser->id,
        'role_id'   => $vtgRole->id,
        'region_id' => $region->id
    ]);
});

test('адміністратор регіону не має права редагувати або видаляти іншого адміністратора цього ж регіону', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();

    $adminOne = User::factory()->create();
    $adminTwo = User::factory()->create();

    $adminRole = Role::where('name', 'admin')->first();

    // Обидва стають адмінами в одному і тому ж регіоні
    RoleRegionUser::create([
        'user_id' => $adminOne->id,
        'role_id' => $adminRole->id,
        'region_id' => $region->id
    ]);
    RoleRegionUser::create([
        'user_id' => $adminTwo->id,
        'role_id' => $adminRole->id,
        'region_id' => $region->id
    ]);

    // 2. ДІЯ: Перший адмін намагається оновити дані другого адміна
    $response = $this->actingAs($adminOne)
        ->patch(route('admin.users.update', [
            'region' => $region->id,
            'user'   => $adminTwo->id
        ]), [
            'name'    => 'Спроба зламати ім\'я',
            'role_id' => $adminRole->id
        ]);

    // 3. ПЕРЕВІРКА: Наша UserPolicy@update повинна заблокувати цю спробу з кодом 403
    $response->assertStatus(403);
});
