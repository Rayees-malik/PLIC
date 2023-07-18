<?php

namespace Database\Factories\AS400;

use App\Models\AS400\AS400WarehouseStock;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;

class AS400WarehouseStockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AS400WarehouseStock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->catalogueActive(),
            'warehouse' => Warehouse::inRandomOrder()->first()->number,
            'unit_cost' => $this->faker->randomFloat(2, 0, 100),
            'quantity' => $this->faker->randomNumber(3, false),
        ];
    }

    public function withoutInventory()
    {
        return $this->state(function (array $attributes) {
            return [
                'quantity' => 0,
            ];
        });
    }
}
