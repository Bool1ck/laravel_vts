<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\WorkType;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workTypes = config('work_types.work_types');
        foreach ($workTypes as $workType) {
            WorkType::factory()->create([
                'name' => $workType,
            ]);
        }
    }
}
