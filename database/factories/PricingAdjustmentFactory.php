<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Models\AS400\AS400Customer;
use App\Models\Brand;
use App\Models\PricingAdjustment;
use App\Models\PricingAdjustmentLineItem;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class PricingAdjustmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PricingAdjustment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'accounts' => [$this->faker->randomNumber(9)],
            'ongoing' => $this->faker->boolean(),
            'start_date' => $this->faker->date(max: now()->subMonth()),
            'end_date' => $this->faker->date(),
            'dollar_discount' => $this->faker->boolean(),
            'dollar_mcb' => $this->faker->boolean(),
            'shared_line' => $this->faker->boolean(),
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

    public function withBrandLineItems($count = 1)
    {
        return $this->has(PricingAdjustmentLineItem::factory()->count($count)->for(Brand::factory(), 'item'), 'lineItems');
    }

    public function withProductLineItems($count = 1)
    {
        return $this->has(PricingAdjustmentLineItem::factory()->for(Product::factory(), 'item')->count($count), 'lineItems');
    }

    public function validAccount()
    {
        return $this->state(function (array $attributes) {
            $customer = AS400Customer::factory()->make();

            return [
                'name' => $customer->name . ' (#' . $customer->customer_number . ')',
                'account' => $customer->customer_number,
                'account_other' => null,
            ];
        });
    }
}
