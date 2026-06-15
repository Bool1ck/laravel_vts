<?php

namespace Tests\Feature\App;

use App\Models\City;
use App\Models\Region;
use App\Models\Role;
use App\Models\Street;
use App\Models\Tp;
use App\Models\User;
use App\Models\RoleRegionUser;
use App\Models\ConnectingPoint;
use App\Services\App\ConnectionPointService;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('сервіс автоматично та правильно розраховує контрольний строк виконання залежно від потужності', function (int $power, string $expected) {
    // 1. ПІДГОТОВКА
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $city = City::factory()->create(['region_id' => $region->id]);
    $street = Street::factory()->create(['city_id' => $city->id]);
    $tp = Tp::factory()->create(['city_id' => $city->id]);

    $service = app(ConnectionPointService::class);

    // Імітуємо ПОВНИЙ набір даних форми згідно з міграцією таблиці
    $formData = [
        'technical_conditions'      => 'ТУ-001/26',             // Обов'язкове поле
        'technical_conditions_date' => '2026-05-01',             // Обов'язкове поле
        'customer'                  => 'ТОВ Тест Покупець',       // Обов'язкове поле
        'customer_type_id'          => 1,
        'payment_date'              => '2026-06-01',
        'power'                     => $power,                   // Потужність із датасету
        'city_id'                   => $city->id,
        'street_id'                 => $street->id,
        'build_number'              => '12',
        'tp_id'                     => $tp->id,
        'powerLineType'             => '0.4',
        'power_line'                => 'Л-1',
        'pole'                      => '15',
        'workTypes'                 => []
    ];

    // 2. ДІЯ
    $connectionPoint = $service->create($region, $formData);

    // 3. ПЕРЕВІРКА
    expect($connectionPoint->perform_by_date)->toBe($expected);

    $this->assertDatabaseHas('connecting_points', [
        'id'              => $connectionPoint->id,
        'perform_by_date' => $expected
    ]);
})->with([
    'до 5 кВт (+45 днів)'     => ['power' => 4,  'expected' => '2026-07-16'],
    'до 16 кВт (+60 днів)'    => ['power' => 12, 'expected' => '2026-07-31'],
    'до 30 кВт (+75 днів)'    => ['power' => 25, 'expected' => '2026-08-15'],
    'понад 30 кВт (+90 днів)' => ['power' => 45, 'expected' => '2026-08-30'],
]);

test('система жорстко забороняє редагувати точку підключення, якщо вона вже виконана (закрита)', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    $mainEngineerRole = Role::where('name', 'Головний інженер')->first();
    RoleRegionUser::create([
        'user_id'   => $user->id,
        'role_id'   => $mainEngineerRole->id,
        'region_id' => $region->id
    ]);

    // Для фабрики створюємо мінімальний набір полів, щоб СУБД пропустила запис
    $completedPoint = ConnectingPoint::factory()->create([
        'region_id'                 => $region->id,
        'technical_conditions'      => 'ТУ-ЗАКРИТО',
        'technical_conditions_date' => '2026-01-01',
        'customer'                  => 'Тестовий Заявник',
        'customer_type_id'          => 1,
        'point_place'               => 'Адреса',
        'power_point'               => 'Опора',
        'power'                     => 10,
        'performance_date'          => '2026-06-15' // Точка виконана
    ]);

    // ДІЯ: Спроба надіслати PATCH запрос на оновлення
    $response = $this->actingAs($user)
        ->patch(route('connection_point.update', ['region' => $region->id, 'cp' => $completedPoint->id]), [
            'name' => 'Спроба змінити закриті дані'
        ]);

    // ПЕРЕВІРКА: Доступ має бути закритий політикою
    $response->assertStatus(403);
});
