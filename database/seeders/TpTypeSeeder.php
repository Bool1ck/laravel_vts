<?php

namespace Database\Seeders;

use App\Models\TpType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TpTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $TpTypes = ['КТП', 'ЗТП', 'ЩТП'];
        foreach ($TpTypes as $TpType) {
            TpType::factory()->create([
                'name' => $TpType
            ]);
        }
    }
}
