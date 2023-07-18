<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400StockData;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400StockDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400StockData::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(),
            'description' => $this->faker->sentence(),
            'category_code' => $this->faker->word(),
            'hide_from_catalogue' => false,
            'out_of_stock' => $this->faker->boolean(),
            // 'last_received' => $this->faker->optional()->date(),
            // 'expected' => $this->faker->optional()->date(),
        ];
    }

    public function inStock()
    {
        return $this->state(fn (array $attributes) => [
            'out_of_stock' => false,
        ]);
    }

    public function outOfStock()
    {
        return $this->state(fn (array $attributes) => [
            'out_of_stock' => true,
        ]);
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'A',
            ];
        });
    }

    public function discontinued()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'D',
            ];
        });
    }

    public function superseded()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'S',
            ];
        });
    }
}
