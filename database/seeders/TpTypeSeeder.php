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
        $TpType = ['КТП', 'ЗТП', 'ЩТП'];
        foreach ($TpType as $type) {
            TpType::factory()->create([
                'name' => $type
            ]);
        }
    }
}
