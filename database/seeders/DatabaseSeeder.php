<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityType;
use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\Role;
use App\Models\Street;
use App\Models\Tp;
use App\Models\User;
use App\Models\WorkType;
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
            TpTypeSeeder::class,
//            ConnectingPointWorkTypeSeeder::class,
        ]);
        Region::factory(10)->create();
        City::factory(10)->create();
        Street::factory(20)->create();
        Tp::factory(100)->create();
        WorkType::factory(5)->create();
        ConnectingPoint::factory(20)->create();
        $this->call([
            ConnectingPointWorkTypeSeeder::class,
        ]);



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
