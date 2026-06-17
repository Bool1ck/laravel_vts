<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    private static string $password;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'boolick',
            'email' => 'boolick@boolick.com',
            'password' => static::$password ??= Hash::make('11111111'),
        ]);
        User::factory()->create([
            'name' => 'bool',
            'email' => 'bool@bool.com',
            'password' => static::$password ??= Hash::make('11111111'),
        ]);
        //
    }
}
