<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\TP;
use App\Models\TPType;
use Database\Seeders\TPTypeSeeder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TP>
 */
class TPFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->numberBetween(1, 800),
            'tp_type_id' => $this->faker->randomElement(TPType::all()->pluck('id')->toArray()),
            'city_id' => $this->faker->randomElement(City::all()->pluck('id')->toArray()),
            //
        ];
    }
}
