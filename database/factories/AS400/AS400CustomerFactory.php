<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_number' => $this->faker->randomNumber(5, true),
            'name' => $this->faker->company(),
            'province' => $this->faker->randomElement([
                'AB', 'BC', 'MB', 'NB', 'NL', 'NS', 'NT',
                'NU', 'ON', 'PE', 'QC', 'SK', 'YT',
            ]),
            'price_code' => $this->faker->words(3, true),
        ];
    }
}
