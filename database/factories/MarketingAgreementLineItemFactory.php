<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\MarketingAgreementLineItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class MarketingAgreementLineItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MarketingAgreementLineItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_id' => Brand::factory()->approved(),
            'activity' => $this->faker->words(3, true),
            'promo_dates' => $this->faker->date(),
            'cost' => $this->faker->randomFloat(2, 0, 9999999),
            'mcb_amount' => $this->faker->optional()->randomFloat(2, 0, 9999999),
        ];
    }
}
