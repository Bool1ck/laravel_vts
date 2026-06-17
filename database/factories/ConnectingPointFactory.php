<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\ConnectingPoint;
use App\Models\CustomerType;
use App\Models\Region;
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
            'technical_conditions' => 'MP-' . fake()->numberBetween(100, 999),
            'technical_conditions_date' => $this->faker->date(),
            'customer' => $this->faker->name() . ' ' . $this->faker->lastName(),
            'customer_type_id' => $this->faker->randomElement(CustomerType::all()->pluck('id')->toArray()),
            'point_place' => $this->faker->words(5, true),
            'power_point' => $this->faker->words(5, true),
            'power' => $this->faker->numberBetween(5, 20),
            //            'payment_date' => $this->faker->date(),
            //            'perform_by_date' => $this->faker->date(),
            //            'planning_date' => $this->faker->date(),
            //            'performance_date' => $this->faker->date(),
            //            'materials_order_date' => $this->faker->date(),
            //            'materials_receipt_date' => $this->faker->date(),
            'contract_date' => $this->faker->date(),
            //            'note' => $this->faker->text(30),
        ];
    }
}
