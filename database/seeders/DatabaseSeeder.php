<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\Region;
use App\Models\Street;
use App\Models\Tp;
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
        // Тестовий сідер для бази
        $this->call(SystemDictionariesSeeder::class);
        $this->call([
            UserSeeder::class,
        ]);
        Region::factory(10)->create();
        City::factory(100)->create();
        Street::factory(1000)->create();
        Tp::factory(100)->create();
        WorkType::factory(5)->create();
        ConnectingPoint::factory(20)->create();
        $this->call([
            ConnectingPointWorkTypeSeeder::class,
        ]);
    }
}
