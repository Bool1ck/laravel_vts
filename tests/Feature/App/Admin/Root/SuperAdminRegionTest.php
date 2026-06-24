<?php

declare(strict_types=1);

namespace Tests\Feature\App\Admin;

use App\Models\Region;
use App\Models\Role;
use App\Models\RoleRegionUser;
use App\Models\User;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('головний суперадміністратор (ID=1) може успішно відкрити список регіонів та створити новий РЕМ', function () {
    $this->seed(SystemDictionariesSeeder::class);

    // Створюємо найпершого користувача в базі даних (гарантовано отримає id = 1)
    DB::statement('ALTER TABLE users AUTO_INCREMENT = 1;');
    $superAdmin = User::factory()->create();

    expect($superAdmin->id)->toBe(1);

    // ІСПРАВЛЕНО: Звертаємось до вашого актуального роуту root.regions.index
    $response = $this->actingAs($superAdmin)
        ->get(route('root.regions.index'));

    $response->assertStatus(200);

    $formData = [
        'name' => 'Західний РЕМ',
    ];

    // ІСПРАВЛЕНО: Звертаємось до роуту збереження root.regions.store
    $response = $this->actingAs($superAdmin)
        ->put(route('root.regions.store'), $formData);

    $response->assertStatus(302)
        ->assertRedirect(route('root.regions.index'));

    $this->assertDatabaseHas('regions', [
        'name' => 'Західний РЕМ',
    ]);
});

test('звичайний адміністратор регіону або інженер отримує відмову 403 при спробі доступу до суперадмінки регіонів', function () {
    $this->seed(SystemDictionariesSeeder::class);

    // Займаємо id = 1 фейковим користувачем
    User::factory()->create();

    // Створюємо звичайного локального адміна
    $localAdmin = User::factory()->create();
    $region = Region::factory()->create();

    $adminRole = Role::where('name', 'admin')->first();
    RoleRegionUser::create([
        'user_id' => $localAdmin->id,
        'role_id' => $adminRole->id,
        'region_id' => $region->id,
    ]);

    // ІСПРАВЛЕНО: Перевіряємо закритий роут root.regions.index
    $response = $this->actingAs($localAdmin)
        ->get(route('root.regions.index'));

    $response->assertStatus(403);
});
