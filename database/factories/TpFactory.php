<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Tp;
use App\Models\TpType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tp>
 */
class TpFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->numberBetween(1, 800),
            'tp_type_id' => $this->faker->randomElement(TpType::all()->pluck('id')->toArray()),
            'city_id' => $this->faker->randomElement(City::all()->pluck('id')->toArray()),
            //
        ];
    }
}
