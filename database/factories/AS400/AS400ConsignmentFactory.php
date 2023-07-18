<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400Consignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400ConsignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400Consignment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "quantity" => $this->faker->boolean(),
        ];
    }
}
