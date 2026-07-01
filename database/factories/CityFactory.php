<?php

declare(strict_types=1);

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
            'name' => $this->faker->unique()->city(),
            'city_type_id' => $this->faker->randomElement(CityType::all()->pluck('id')->toArray()),
            'region_id' => $this->faker->randomElement(Region::all()->pluck('id')->toArray()),
        ];
    }
}
