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
        $PowerLineType = ['0.4', '10'];
        foreach ($PowerLineType as $type) {
            PowerLineType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
