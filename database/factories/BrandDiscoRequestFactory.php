<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\BrandDiscoRequest;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BrandDiscoRequestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BrandDiscoRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_id' => Brand::factory(),
            'submitted_by' => User::factory(),
            'name' => $this->faker->words(3, true),
            'reason' => $this->faker->sentence(),
            'recoup_plan' => $this->faker->sentence(),
            'ap_owed' => $this->faker->numberBetween(100, 1000),
            'ytd_sales' => $this->faker->numberBetween(100, 1000),
            'ytd_margin' => $this->faker->numberBetween(100, 1000),
            'previous_year_sales' => $this->faker->numberBetween(100, 1000),
            'previous_year_margin' => $this->faker->numberBetween(100, 1000),
            'inventory_value' => $this->faker->numberBetween(100, 1000),
        ];
    }
}
