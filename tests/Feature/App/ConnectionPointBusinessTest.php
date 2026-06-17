<?php

declare(strict_types=1);

namespace Tests\Feature\App;

use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoleRegionUser;
use App\Models\User;
use Database\Seeders\SystemDictionariesSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('система автоматичного розрахунку дати виконання спрацьовує при внесенні дати оплати', function (int $power, string $expected) {

    // 1. ПІДГОТОВКА: Розгортаємо довідники ролей
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    // Видаємо користувачу роль ВТГ (інженер, який має право редагувати точки)
    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $region->id,
    ]);

    // Створюємо базову точку ПДК в базі (без дати оплати та без розрахованого строку)
    $connectionPoint = ConnectingPoint::factory()->create([
        'region_id' => $region->id,
        'technical_conditions' => 'ТУ-001/26',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Тестовий Клієнт',
        'point_place' => 'Адреса оригінальна',
        'power_point' => 'Опора оригінальна',
        'power' => 5,
        'payment_date' => null,
        'perform_by_date' => null,
    ]);

    // Дані, які інженер вносить на формі РЕДАГУВАННЯ
    $updateData = [
        'technical_conditions' => 'ТУ-001/26',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Тестовий Клієнт',
        'customer_type_id' => $connectionPoint->customer_type_id, // Беремо реальний ID створеного типу
        'point_place' => 'Адреса оригінальна',
        'power_point' => 'Опора оригінальна',
        'payment_date' => '2026-06-01', // Фіксуємо дату оплати!
        'power' => $power,        // Потужність прилітає з датасету Pest
        'workTypes' => [],
    ];

    // 2. ДІЯ: Робот шле PATCH запит на оновлення
    $response = $this->actingAs($user)
        ->patch(route('connection_point.update', ['region' => $region->id, 'cp' => $connectionPoint->id]), $updateData);

    // Перевіряємо редирект на картку перегляду (успішне оновлення)
    $response->assertStatus(302)
        ->assertRedirect(route('connection_point.show', ['region' => $region->id, 'cp' => $connectionPoint->id]));

    // 3. ПЕРЕВІРКА: Перевіряємо, чи спрацював калькулятор у сервісі updateFull
    $this->assertDatabaseHas('connecting_points', [
        'id' => $connectionPoint->id,
        'payment_date' => '2026-06-01',
        'perform_by_date' => $expected, // Строк має математично розрахуватись
    ]);
})->with([
    'до 5 кВт (+45 днів)' => ['power' => 4,  'expected' => '2026-07-16'],
    'до 16 кВт (+60 днів)' => ['power' => 12, 'expected' => '2026-07-31'],
    'до 30 кВт (+75 днів)' => ['power' => 25, 'expected' => '2026-08-15'],
    'понад 30 кВт (+90 днів)' => ['power' => 45, 'expected' => '2026-08-30'],
]);

test('система жорстко забороняє редагувати точку підключення, якщо вона вже виконана (закрита)', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    $VTGRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $VTGRole->id,
        'region_id' => $region->id,
    ]);

    $completedPoint = ConnectingPoint::factory()->create([
        'region_id' => $region->id,
        'technical_conditions' => 'ТУ-ЗАКРИТО',
        'technical_conditions_date' => '2026-01-01',
        'customer' => 'Тестовий Заявник',
        'point_place' => 'Адреса',
        'power_point' => 'Опора',
        'power' => 10,
        'performance_date' => '2026-06-15', // Точка закрита
    ]);

    $response = $this->actingAs($user)
        ->patch(route('connection_point.update', ['region' => $region->id, 'cp' => $completedPoint->id]), [
            'name' => 'Спроба змінити закриті дані',
        ]);

    $response->assertStatus(403);
});

test('користувач із роллю ВТГ має право успішно оновити всі дані форми ТДК', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $region->id,
    ]);

    // Створюємо точку з повним набором обов'язкових полів СУБД
    $cp = ConnectingPoint::factory()->create([
        'region_id' => $region->id,
        'technical_conditions' => 'ТУ-001',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Старий Заявник',
        'point_place' => 'Адреса',
        'power_point' => 'Опора',
        'power' => 10,
    ]);

    $updateData = [
        'technical_conditions' => 'ТУ-001',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Новий Заявник',
        'customer_type_id' => $cp->customer_type_id,
        'point_place' => 'Адреса нова',
        'power_point' => 'Опора нова',
        'payment_date' => '2026-06-01',
        'power' => 10,
        'workTypes' => [],
    ];

    $response = $this->actingAs($user)
        ->patch(route('connection_point.update', ['region' => $region->id, 'cp' => $cp->id]), $updateData);

    //    $response->dumpSession();
    $response->assertStatus(302);

    $this->assertDatabaseHas('connecting_points', [
        'id' => $cp->id,
        'customer' => 'Новий Заявник',
    ]);
});

test('Головний інженер може змінювати тільки дату планування, інші поля ігноруються', function () {
    // 1. ПІДГОТОВКА:
    $this->seed(SystemDictionariesSeeder::class);

    $region = Region::factory()->create();
    $user = User::factory()->create();

    // Перевіряємо роль "Головний інженер"
    $mainEngineerRole = Role::where('name', 'Головний інженер')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $mainEngineerRole->id,
        'region_id' => $region->id,
    ]);

    // Створюємо точку з повним набором обов'язкових полів СУБД
    $cp = ConnectingPoint::factory()->create([
        'region_id' => $region->id,
        'technical_conditions' => 'ТУ-ОРИГІНАЛ',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Оригінальний Заявник',
        'point_place' => 'Адреса оригінальна',
        'power_point' => 'Опора оригінальна',
        'power' => 10,
        'planning_date' => null,
    ]);

    // Імітуємо повну форму редагування: ME-Request пропустить тільки planning_date,
    // але інші required-поля ми зобов'язані передати, щоб форма пройшла базову валідацію HTTP
    $updateData = [
        'technical_conditions' => 'ТУ-ОРИГІНАЛ',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Хакерська Спроба Змінити', // Буде проігноровано сервісом/реквестом
        'customer_type_id' => $cp->customer_type_id,
        'point_place' => 'Адреса оригінальна',
        'power_point' => 'Опора оригінальна',
        'power' => 10,
        'planning_date' => '2026-07-01', // Дозволене поле для ME
    ];

    // 2. ДІЯ: Головний інженер відправляє форму оновлення
    $response = $this->actingAs($user)
        ->patch(route('connection_point.update', ['region' => $region->id, 'cp' => $cp->id]), $updateData);

    // 3. ПЕРЕВІРКА: Успішний редирект
    $response->assertStatus(302);

    // Перевіряємо, що дата планування успішно оновилася в базі даних
    $this->assertDatabaseHas('connecting_points', [
        'id' => $cp->id,
        'planning_date' => '2026-07-01',
    ]);

    // Перевіряємо, що ім'я клієнта залишилося СТАРИМ (спроба хакінгу відсічена вашим UpdateConnectionPointMERequest)
    $this->assertDatabaseHas('connecting_points', [
        'id' => $cp->id,
        'customer' => 'Оригінальний Заявник',
    ]);
});

test('користувач ролі ВТГ не має доступу до перегляду картки ТДК з іншого регіону', function () {

    $this->seed(SystemDictionariesSeeder::class);

    // Створюємо два різних регіони
    $regionOne = Region::factory()->create();
    $regionTwo = Region::factory()->create();

    $user = User::factory()->create();

    // Наш інженер працює тільки в Регіоні №1
    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $regionOne->id,
    ]);

    // Створюємо точку підключення, яка належить чужому Регіону №2
    $cpInRegionTwo = ConnectingPoint::factory()->create([
        'region_id' => $regionTwo->id,
        'technical_conditions' => 'ТУ-ЧУЖИЙ',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Чужий Клієнт',
        'point_place' => 'Адреса',
        'power_point' => 'Опора',
        'power' => 10,
    ]);

    // ІСПРАВЛЕНО: Запитуємо роут СВОГО Регіону 1, але підставляємо ID точки з чужого Регіону 2
    $response = $this->actingAs($user)
        ->get(route('connection_point.show', ['region' => $regionOne->id, 'cp' => $cpInRegionTwo->id]));

    // ПЕРЕВІРКА: Система має повернути 403 Forbidden (Доступ заборонено)
    $response->assertStatus(404);
});

test('користувач ролі ВТГ заборонено оновлювати дані картки ТДК з чужого регіону', function () {
    $this->seed(SystemDictionariesSeeder::class);

    $regionOne = Region::factory()->create();
    $regionTwo = Region::factory()->create();

    $user = User::factory()->create();

    // Інженер прив'язаний тільки до Регіону №1
    $vtgRole = Role::where('name', 'ВТГ')->first();
    RoleRegionUser::create([
        'user_id' => $user->id,
        'role_id' => $vtgRole->id,
        'region_id' => $regionOne->id,
    ]);

    // Точка належить Регіону №2
    $cpInRegionTwo = ConnectingPoint::factory()->create([
        'region_id' => $regionTwo->id,
        'technical_conditions' => 'ТУ-ЧУЖИЙ',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Чужий Клієнт',
        'point_place' => 'Адреса',
        'power_point' => 'Опора',
        'power' => 10,
    ]);

    $updateData = [
        'technical_conditions' => 'ТУ-ХАК',
        'technical_conditions_date' => '2026-05-01',
        'customer' => 'Спроба Змінити Чуже',
        'customer_type_id' => $cpInRegionTwo->customer_type_id,
        'point_place' => 'Адреса',
        'power_point' => 'Опора',
        'power' => 10,
        'workTypes' => [],
    ];

    // ІСПРАВЛЕНО: Шлемо PATCH на свій Регіон 1, але намагаємось оновити чужу точку cpId
    $response = $this->actingAs($user)
        ->patch(route('connection_point.update', ['region' => $regionOne->id, 'cp' => $cpInRegionTwo->id]), $updateData);

    // ПЕРЕВІРКА: Очікуємо жорстке блокування доступу 403
    $response->assertStatus(404);
});
