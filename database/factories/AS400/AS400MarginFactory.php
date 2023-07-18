<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400Margin;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400MarginFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400Margin::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_id' => Brand::factory(),
            'margin' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
