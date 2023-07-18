<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400Freight;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400FreightFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400Freight::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_id' => Brand::factory(),
            'freight_included' => $this->faker->boolean(),
            'freight' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
