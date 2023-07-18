<?php

namespace Database\Factories;

use App\Models\FutureLandedCost;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class FutureLandedCostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FutureLandedCost::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'landed_cost' => $this->faker->randomFloat(2, 0, 100),
            'change_date' => $this->faker->dateTimeBetween('tomorrow', '+1 year'),
        ];
    }
}
