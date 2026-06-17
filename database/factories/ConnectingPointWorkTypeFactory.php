<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ConnectingPoint;
use App\Models\ConnectingPointWorkType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConnectingPointWorkType>
 */
class ConnectingPointWorkTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //            'worktype_id' => $this->faker->randomElement(ConnectingPointWorkType::all()->pluck('id')->toArray()),
            //            'pointid' => $this->faker->randomElement(ConnectingPoint::all()->pluck('id')->toArray()),
        ];
    }
}
