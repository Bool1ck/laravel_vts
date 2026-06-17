<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ConnectingPoint;
use App\Models\ConnectingPointWorkType;
use App\Models\WorkType;
use Illuminate\Database\Seeder;

class ConnectingPointWorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $points = ConnectingPoint::all();
        $works = WorkType::all();
        foreach ($points as $point) {
            $rand_keys = array_rand(WorkType::all()->pluck('id')->toArray(), 1);
            ConnectingPointWorkType::factory()->create([
                'pointid' => $point->id,
                'worktype_id' => $works[$rand_keys],
            ]);
        }
        //
    }
}
