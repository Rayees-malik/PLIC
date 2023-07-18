<?php

namespace Database\Factories;

use App\Models\InventoryRemovalLineItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryRemovalLineItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryRemovalLineItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory()->approved(),
            'cost' => $this->faker->randomFloat(2, 0, 100),
            'quantity' => $this->faker->randomNumber(3, false),
            'expiry' => $this->faker->date(),
            'warehouse' => $this->faker->numerify('##'),
            'full_mcb' => $this->faker->boolean(),
            'reserve' => $this->faker->boolean(),
            'notes' => $this->faker->sentence(),
            'deleted_at' => null,
            'reason' => $this->faker->sentence(),
            'vendor_pickup' => $this->faker->boolean(),
        ];
    }
}
