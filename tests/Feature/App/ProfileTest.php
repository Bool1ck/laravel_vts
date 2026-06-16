<?php

namespace Tests\Feature\App;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('користувач може успішно відкрити сторінку свого профілю', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)
        ->get(route('profile.edit'));

    $response->assertStatus(200);
});

test('користувач у своєму профілі може успішно змінити свій пароль', function () {
    // 1. ПІДГОТОВКА: Створюємо користувача з відомим стартовим паролем
    $user = User::factory()->create([
        'password' => Hash::make('old-password-123'),
    ]);

    // Дані форми зміни пароля згідно з логікою вашого PasswordController
    $passwordData = [
        'current_password'      => 'old-password-123',
        'password'              => 'new-secure-password',
        'password_confirmation' => 'new-secure-password',
    ];

    // 2. ДІЯ: Робот відправляєте PUT-запит на роут смени пароля
    $response = $this->actingAs($user)
        ->put(route('password.update'), $passwordData);

    // 3. ПЕРЕВІРКА: Система повинна зробити редирект назад на сторінку профілю
    $response->assertStatus(302)
        ->assertRedirect('/');

    // Оновлюємо модель користувача з бази даних
    $user->refresh();

    // ІСПРАВЛЕНО: Перевіряємо саме той пароль, який відправляли у формі ('new-secure-password')
    expect(Hash::check('new-secure-password', $user->password))->toBeTrue();
});
