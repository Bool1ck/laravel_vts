<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\CustomerType;
use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Побутовий', 'Юридічний'];
        foreach ($types as $type) {
            CustomerType::factory()->create([
                'name' => $type,
            ]);
        }
    }
}
