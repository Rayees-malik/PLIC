<?php

namespace Database\Factories;

use App\Models\ApiUser;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApiUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ApiUser::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }
}
