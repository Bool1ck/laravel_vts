<?php

namespace Database\Seeders;

use App\Models\TPType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TPTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $TpType = ['КТП', 'ЗТП', 'ЩТП'];
        foreach ($TpType as $type) {
            TPType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
