<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'role_id' => DB::table('roles')->where('name', 'root')->value('id'),
//            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('11111111'),
//            'remember_token' => Str::random(10),
        ]);
        //
    }
}
