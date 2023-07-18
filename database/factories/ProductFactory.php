<?php

namespace Database\Factories;

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\AS400\AS400StockData;
use App\Models\AS400\AS400WarehouseStock;
use App\Models\Brand;
use App\Models\CatalogueCategory;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductSubcategory;
use App\Models\UnitOfMeasure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $productCategory = ProductCategory::all()->random()->id;
        $subcategories = ProductSubcategory::byCategory($productCategory)->get();
        $productSubcategory = $subcategories->count() > 0 ? $subcategories->random()->id : null;

        return [
            'stock_id' => $this->faker->randomNumber(6, true),
            'name' => $this->faker->words(3, true),
            'brand_id' => Brand::factory(),
            'state' => SignoffStateHelper::INITIAL,
            'status' => StatusHelper::ACTIVE,
            'supersedes_id' => null,
            'is_display' => $this->faker->boolean(),
            'country_origin' => Country::all()->random()->id,
            'country_shipped' => Country::all()->random()->id,
            'packaging_language' => $this->faker->randomKey(Product::PACKAGING_LANGUAGES),
            'category_id' => $productCategory,
            'upc' => $this->faker->ean13(),
            'subcategory_id' => $productSubcategory,
            'catalogue_category_id' => CatalogueCategory::factory(),
            'not_for_resale' => $this->faker->boolean(),
            'purity_sell_by_unit' => $this->faker->randomKey(Product::SELL_BY_UNITS),
            'inner_units' => $this->faker->randomNumber(2, false),
            'master_units' => $this->faker->randomNumber(2, false),
            'uom_id' => UnitOfMeasure::all()->random()->id,
            'tester_available' => $this->faker->boolean(),
            'shelf_life_units' => $this->faker->randomElement(['months', 'years']),
            'retailer_sell_by_unit' => $this->faker->randomElement([1, 2, 3, 5, 6, 7]),  // sum or key values SELL_BY_UNITS
        ];
    }

    public function withImages()
    {
        return $this->afterCreating(function (Product $product) {
            $fakeProductImage = UploadedFile::fake()->image(uniqid() . '.jpg');
            $product->addMedia($fakeProductImage)->toMediaCollection('product');

            $fakeLabelFlatImage = UploadedFile::fake()->image(uniqid() . '.jpg');
            $product->addMedia($fakeLabelFlatImage)->toMediaCollection('label_flat');
        });
    }

    public function approved()
    {
        return $this
            ->afterCreating(function (Product $product) {
                $product = $product->duplicate();
                $product->state = SignoffStateHelper::APPROVED;
                $product->save();
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

    public function catalogueActive(bool $inStock = true)
    {
        return $this->has(AS400StockData::factory()
            ->active()
            ->when(
                $inStock,
                fn ($query) => $query->inStock(),
                fn ($query) => $query->outOfStock()
            ), 'as400StockData');
    }

    public function catalogueDiscontinued(bool $inStock = true)
    {
        return $this->has(AS400StockData::factory()
            ->discontinued()
            ->when(
                $inStock,
                fn ($query) => $query->inStock(),
                fn ($query) => $query->outOfStock()
            ), 'as400StockData');
    }

    public function warehouse(int $warehouseNumber = 1, int $inventoryQuantity = null)
    {
        return $this->has(AS400WarehouseStock::factory()->state(fn () => [
            'warehouse' => $warehouseNumber ?? 1,
            'quantity' => $inventoryQuantity ?? $this->faker->randomNumber(3),
        ]), 'as400WarehouseStock');
    }
}
