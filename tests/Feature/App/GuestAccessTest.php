<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

// Очищаем базу перед тестом, чтобы всё работало в стерильных условиях
uses(RefreshDatabase::class);

test('Доступ без авторизації до головної сторінки', function () {
    // Робот пытается зайти на Dashboard будучи гостем
    $response = $this->get(route('dashboard'));

    // Проверяем, что гостя перенаправляет (302) на страницу логина
    $response->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('Доступ без авторизації до адмін-панелі міст', function () {
    // Робот пытается прорваться в админку городов региона №1 в обход авторизации
    // (Маршрут 'admin.cities.index' мы смотрели в вашем web.php)
    $response = $this->get(route('admin.cities.index', ['region' => 1]));

    // Система обязана заблокировать гостя и отправить на авторизацию
    $response->assertStatus(302)
        ->assertRedirect(route('login'));
});
