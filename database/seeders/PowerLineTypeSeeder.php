<?php

namespace Database\Seeders;

use App\Models\PowerLineType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PowerLineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $PowerLineTypes = ['0.4', '10'];
        foreach ($PowerLineTypes as $PowerLineType) {
            PowerLineType::factory()->create([
                'name' => $PowerLineType
            ]);
        }
    }
}
