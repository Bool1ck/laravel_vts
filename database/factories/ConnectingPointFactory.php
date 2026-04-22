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
            'point_place' => $this->faker->words(5, true),
            'connecting_note' => $this->faker->text(30),
            'power_point' => $this->faker->words(5, true),
            'power_point_note' => $this->faker->text(30),
            'power' => $this->faker->numberBetween(5,20),
            'note' => $this->faker->text(30),
        ];
    }
}
