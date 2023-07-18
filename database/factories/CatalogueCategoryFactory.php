<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\CatalogueCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CatalogueCategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CatalogueCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'brand_id' => Brand::factory(),
            'name' => $this->faker->words(3, true),
        ];
    }
}
