<?php

namespace Database\Seeders;

use App\Models\StreetType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StreetTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $streetTypes = config('street_types.street_types');
        foreach ($streetTypes as $streetType) {
            StreetType::factory()->create([
                'name' => $streetType
            ]);
        }
    }
}
