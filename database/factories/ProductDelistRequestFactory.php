<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Models\Product;
use App\Models\ProductDelistRequest;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDelistRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductDelistRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'submitted_by' => User::factory(),
            'product_id' => Product::factory()->approved()->create()->id,
            'reason' => $this->faker->sentence(),
            'state' => SignoffStateHelper::INITIAL,
        ];
    }
}
