<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\CityType;
use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city(),
            'city_type_id' => fake()->randomElement(CityType::all()->pluck('id')->toArray()),
            'region_id' => fake()->randomElement(Region::all()->pluck('id')->toArray()),
        ];
    }
}
