<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Models\Brand;
use App\Models\Promo;
use App\Models\PromoLineItem;
use App\Models\PromoPeriod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PromoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Promo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'state' => SignoffStateHelper::INITIAL,
            'brand_id' => Brand::factory(),
            'period_id' => PromoPeriod::factory()->startsInPast()->endsInFuture(),
        ];
    }

    public function approved()
    {
        return $this
            ->afterCreating(function (Promo $promo) {
                $promo = $promo->duplicate();
                $promo->state = SignoffStateHelper::APPROVED;
                $promo->save();
            });
    }

    public function withLineItems($count = 1)
    {
        return $this->has(PromoLineItem::factory()->count($count), 'lineItems');
    }
}
