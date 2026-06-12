<?php

namespace Database\Seeders;

use App\Models\CityType;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cityTypes = config('city_types.city_types');
        foreach ($cityTypes as $cityType) {
            CityType::factory()->create([
                'name' => $cityType
            ]);
        }
    }
}
