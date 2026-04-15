<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityType;
use App\Models\Region;
use App\Models\Role;
use App\Models\Street;
use App\Models\TP;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CityTypeSeeder::class,
            StreetTypeSeeder::class,
            PowerLineTypeSeeder::class,
            TPTypeSeeder::class,
        ]);
        Region::factory(10)->create();
        City::factory(10)->create();
        Street::factory(20)->create();
        TP::factory(100)->create();




//         User::factory(10)->create();
//        Role::factory()->create([
//            'name' => 'admin',
//        ]);

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
    }
}
