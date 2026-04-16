<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\ConnectingPoint;
use App\Models\PowerLineType;
use App\Models\Region;
use App\Models\Street;
use App\Models\TpType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConnectingPoint>
 */
class ConnectingPointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'region_id' => $this->faker->randomElement(Region::all()->pluck('id')->toArray()),
            'technical_conditions' => 'MP-'.fake()->numberBetween(100, 999),
            'technical_conditions_date' => $this->faker->date(),
            'customer' => $this->faker->name().' '.$this->faker->lastName(),
            'city_id' => $this->faker->randomElement(City::all()->pluck('id')->toArray()),
            'street_id' => $this->faker->randomElement(Street::all()->pluck('id')->toArray()),
            'building_number' => $this->faker->numberBetween(10,100),
            'connecting_note' => $this->faker->text(30),
            'power_line_type_id' => $this->faker->randomElement(PowerLineType::all()->pluck('id')->toArray()),
            'tps_id' => $this->faker->randomElement(TpType::all()->pluck('id')->toArray()),
            'line' => fake()->numberBetween(1, 10),
            'pole' => fake()->numberBetween(1, 50),
            'power_point_note' => $this->faker->text(30),
            'note' => $this->faker->text(30),

            //
        ];
    }
}
