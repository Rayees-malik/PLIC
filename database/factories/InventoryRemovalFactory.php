<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Models\InventoryRemoval;
use App\Models\InventoryRemovalLineItem;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryRemovalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryRemoval::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'submitted_by' => User::factory(),
            'name' => $this->faker->word(),
            'comment' => $this->faker->sentence(),
            'deleted_at' => null,
            'state' => SignoffStateHelper::PENDING,
            'vendor_pickup' => $this->faker->boolean(),
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::PENDING,
            ];
        });
    }

    public function initial()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::INITIAL,
            ];
        });
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::APPROVED,
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::REJECTED,
            ];
        });
    }

    public function archived()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::ARCHIVED,
            ];
        });
    }

    public function inProgress()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::IN_PROGRESS,
            ];
        });
    }

    public function unsubmitted()
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => SignoffStateHelper::UNSUBMITTED,
            ];
        });
    }

    public function withLineItems($count = 1, string $warehouseNumber = null)
    {
        return $this->has(InventoryRemovalLineItem::factory()
            ->count($count)
            ->when($warehouseNumber, function ($query, $warehouseNumber) {
                return $query->state(function (array $attributes, InventoryRemoval $inventoryRemoval) use ($warehouseNumber) {
                    return ['warehouse' => $warehouseNumber];
                });
            }), 'lineItems');
    }
}
