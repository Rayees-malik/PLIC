<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400ZeusRetailer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400ZeusRetailerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400ZeusRetailer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'invoice_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'category' => $this->faker->randomElement(['MODU', 'KYOL']),
            'customer_number' => $this->faker->randomNumber(5, true),
            'name' => $this->faker->company(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'province' => $this->faker->stateAbbr(),
            'postal_code' => $this->faker->postcode(),
            'contact_email' => $this->faker->safeEmail(),
            'contact_phone' => $this->faker->phoneNumber(),
        ];
    }

    public function kyolic()
    {
        return $this->state(
            fn (array $attributes) => ['category' => 'KYOL']
        );
    }

    public function moducare()
    {
        return $this->state(
            fn (array $attributes) => ['category' => 'MODU']
        );
    }
}
