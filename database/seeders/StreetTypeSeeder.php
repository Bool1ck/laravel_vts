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
        $streetType = ['вул.', 'пров.', 'проспект'];
        foreach ($streetType as $type) {
            StreetType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
