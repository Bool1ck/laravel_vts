<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Street;
use App\Models\StreetType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Street>
 */
class StreetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->streetName(),
            'city_id' => $this->faker->randomElement(City::all()->pluck('id')->toArray()),
            'street_type_id' => $this->faker->randomElement(StreetType::all()->pluck('id')->toArray()),
            //
        ];
    }
}
