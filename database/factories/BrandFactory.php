<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\AS400\AS400Consignment;
use App\Models\Brand;
use App\Models\Currency;
use App\Models\Vendor;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'vendor_id' => Vendor::factory(),
            'name' => $this->faker->words(3, true),
            'name_fr' => $this->faker->words(3, true),
            'made_in_canada' => false,
            // 'brand_number' =>,
            // 'category_code' =>,
            // 'broker_proposal' =>,
            'currency_id' => 1,
            // 'website' =>,
            // 'phone' =>,
            'description' => $this->faker->sentence(),
            'description_fr' => $this->faker->sentence(),
            // 'unpublished_new_listing_deal' => ,
            // 'unpublished_new_listing_deal_fr' =>,
            // 'catalogue_notice' =>,
            // 'catalogue_notice_fr' =>,
            'contract_exclusive' => false,
            'no_other_distributors' => false,
            // 'also_distributed_by' =>,
            'allows_amazon_resale' => false,
            'map_pricing' => false,
            'minimum_order_quantity' => $this->faker->optional()->randomNumber(),
            'minimum_order_type' => '$',
            'shipping_lead_time' => $this->faker->optional()->randomNumber(),
            // 'product_availability' =>,
            'nutrition_house_payment_type' => $this->faker->randomElement(['purity', 'vendor']),
            'nutrition_house' => $this->faker->boolean(),
            // 'nutrition_house_payment' =>,            'state' =>,
            'health_first_payment_type' => $this->faker->randomElement(['purity', 'vendor']),
            'health_first' => $this->faker->boolean(),
            // 'health_first_payment' =>,default
            'allow_oi' => false,
            // 'default_pl_discount' =>,
            'purchasing_specialist_id' => User::factory(),
            'vendor_relations_specialist_id' => User::factory(),
            'in_house_brand' => false,
            'business_partner_program' => false,
            'hide_from_exports' => false,
            'state' => SignoffStateHelper::INITIAL,
            'status' => StatusHelper::ACTIVE,
            'education_portal' => $this->faker->boolean(),
        ];
    }

    public function approved()
    {
        return $this
            ->afterCreating(function (Brand $brand) {
                $brand = $brand->duplicate();
                $brand->state = SignoffStateHelper::APPROVED;
                $brand->save();
            });
    }

    public function discontinued()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusHelper::DISCONTINUED,
            ];
        });
    }

    public function active()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => StatusHelper::ACTIVE,
            ];
        });
    }

    public function usd()
    {
        return $this->state(function () {
            return [
                'currency_id' => Currency::where('name', 'USD')->first()->id,
            ];
        });
    }

    public function cad()
    {
        return $this->state(function () {
            return [
                'currency_id' => Currency::where('name', 'CAD')->first()->id,
            ];
        });
    }

    public function consignment()
    {
        return $this->has(AS400Consignment::factory(), 'as400Consigment');
    }
}
