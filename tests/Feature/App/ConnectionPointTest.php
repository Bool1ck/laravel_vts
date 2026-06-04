<?php

use App\Models\City;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Services\App\ConnectionPointService;
use Illuminate\Foundation\Testing\RefreshDatabase;

// ТРЕЙТ (Инструмент): RefreshDatabase говорит Laravel:
// "Перед запуском теста полностью очисти тестовую базу laravel_vts_test,
// накати миграции, а после теста сотри всё, что робот там насоздавал".
uses(RefreshDatabase::class);

// Наш первый тест (Блок-инструкция для робота)
test('авторизованный инженер региона может получить данные городов через AJAX', function () {

    // --- ЭТАП 1: ПОДГОТОВКА (Создаем фейковый мир для теста) ---

    // Робот создает один фейковый регион в базе данных
    $region = Region::factory()->create();

    $cityType = \App\Models\CityType::factory()->create();

    // Робот создает город и явно привязывает его к созданному выше региону
    $city = City::factory()->create(['region_id' => $region->id]);

    // Робот создает фейкового пользователя (пока без прав)
    $user = User::factory()->create();

    // Робот находит в базе роль 'engineer' (она там есть благодаря нашему сидеру)
    $engineerRole = Role::where('name', 'engineer')->first();

    // Робот связывает пользователя и роль в контексте нашего региона
    $user->roles()->attach($engineerRole->id, ['region_id' => $region->id]);


    // --- ЭТАП 2: ДЕЙСТВИЕ (Имитируем клик или AJAX-запрос) ---

    $response = $this->actingAs($user) // Робот логинится под созданным пользователем
    ->json('GET', route('api.v1.city-data.create', [ // Делает AJAX (JSON) запрос типа GET
        'region' => $region->id,
        'city' => $city->id
    ]));


    // --- ЭТАП 3: ПРОВЕРКА (Убеждаемся, что всё работает правильно) ---

    // Робот проверяет, что сервер ответил кодом 200 (ОК). Если там 403 или 500 — тест упадет.
    $response->assertStatus(200)
        // Робот проверяет, что в пришедшем ответе есть разделы 'streets' и 'tps' для наших селектов
        ->assertJsonStructure([
            'streets',
            'tps'
        ]);
});
