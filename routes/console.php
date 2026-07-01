<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

// =========================================================================
// ПЛАНУВАЛЬНИК ЗАДАЧ (AUTOMATED SCHEDULE)
// =========================================================================

// Автоматичне щоденне обнулення та повний перезапис демо-пісочниці о 03:00 ночі
Schedule::command('db:seed --class=DemoSandboxSeeder')
    ->dailyAt('03:00')
    ->runInBackground()
    ->appendOutputTo(storage_path('logs/sandbox_reset.log'))
    // ІСПРАВЛЕНО: Задача виконається ТІЛЬКИ якщо поточне середовище додатка — staging
    ->when(function () {
        return config('app.env') === 'sandbox';
    });
