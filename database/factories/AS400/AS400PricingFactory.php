<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400Pricing;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400PricingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400Pricing::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'wholesale_price' => $this->faker->randomFloat(2, 0, 100),
            'average_landed_cost' => $this->faker->randomFloat(2, 0, 100),
            'duty' => $this->faker->randomFloat(2, 0, 100),
            'edlp_discount' => $this->faker->randomFloat(2, 0, 100),
            'po_price' => $this->faker->randomFloat(2, 0, 100),
            'po_price_expiry' => $this->faker->dateTimeBetween('-1 year', '+1 year'),
            'taxable' => $this->faker->boolean(),
        ];
    }
}
