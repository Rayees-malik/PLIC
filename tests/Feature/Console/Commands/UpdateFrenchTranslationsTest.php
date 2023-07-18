<?php

use Altek\Accountant\Context;
use App\Helpers\SignoffStateHelper;
use App\Models\Brand;
use App\Models\CatalogueCategory;
use App\Models\Product;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use TiMacDonald\Log\LogFake;
use YlsIdeas\FeatureFlags\Facades\Features;

use function Pest\Laravel\artisan;

uses(DatabaseTransactions::class);

beforeEach(function () {
    Log::swap(new LogFake);
    Config::set('accountant.contexts', Context::WEB | Context::TEST);
    Features::fake(['remove-session-dependency' => true]);

    $this->signIn()->assign('admin');
});

/**
 * Guards tests
 */
it('aborts if the file cannot be found', function () {
    artisan('update:french-translations', [
        'translationFile' => 'file_that_does_not_exist.xslx',
        '--cutoff' => now()->format('Y-m-d'),
    ])->expectsOutput('File "file_that_does_not_exist.xslx" does not exist');
});

it('does not perform the update unless the user confirms twice', function () {
    $product = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $brand = Brand::factory()->create([
        'name' => 'English Brand Name',
        'name_fr' => null,
        'description' => 'English brand description',
        'description_fr' => null,
    ]);

    $catalogueCategory = CatalogueCategory::factory()->create([
        'name' => 'English Catalogue Category Name',
        'name_fr' => null,
    ]);

    artisan('update:french-translations', [
        'translationFile' => 'tests/files/update_french_translations_test.xlsx',
        '--cutoff' => now()->format('Y-m-d'),
    ])->expectsConfirmation('Are you sure you want to update the translations?', 'yes')
        ->expectsConfirmation('Are you REALLY sure you want to update the translations?  THIS CAN NOT BE UNDONE!', 'no');

    $product->refresh();
    $brand->refresh();
    $catalogueCategory->refresh();

    expect($product->name_fr)->toBeNull();
    expect($product->description_fr)->toBeNull();
    expect($brand->name_fr)->toBeNull();
    expect($brand->description_fr)->toBeNull();
    expect($catalogueCategory->name_fr)->toBeNull();
});

it('aborts if there are no rows', function () {
    artisan('update:french-translations', [
        'translationFile' => 'tests/files/empty_update_french_translations_test.xlsx',
        '--cutoff' => now()->format('Y-m-d'),
    ])->expectsOutput('No rows found in the file.');
});

/**
 * Functional tests
 */
it('can process multiple rows', function () {
    [
        'firstProduct' => $firstProduct,
        'firstBrand' => $firstBrand,
        'firstCatalogueCategory' => $firstCatalogueCategory,
        'secondBrand' => $secondBrand,
        'secondProduct' => $secondProduct,
        'secondCatalogueCategory' => $secondCatalogueCategory
    ] = buildWorld();

    runCommandWithPopulatedFile();

    // check the first row of data
    $firstProduct->refresh();
    $firstBrand->refresh();
    $firstCatalogueCategory->refresh();

    expect($firstProduct->name_fr)->toBe('French Product Name');
    expect($firstProduct->description_fr)->toBe('French product description');
    expect($firstBrand->description_fr)->toBe('French brand description');
    expect($firstCatalogueCategory->name_fr)->toBe('French Catalogue Category Name');

    // check the second row of data
    $secondProduct->refresh();
    $secondBrand->refresh();
    $secondCatalogueCategory->refresh();

    expect($secondProduct->name_fr)->toBe('Second French Product Name');
    expect($secondProduct->description_fr)->toBe('Second French product description');
    expect($secondBrand->description_fr)->toBe('Second French brand description');
    expect($secondCatalogueCategory->name_fr)->toBe('Second French Catalogue Category Name');
});

/**
 * Product tests
 */
it('can update a product', function () {
    $product = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $product->refresh();

    expect($product->name_fr)->toBe('French Product Name');
    expect($product->description_fr)->toBe('French product description');
});

it('updates a product name and description if there are no historical ledger records', function () {
    Config::set('accountant.contexts', Context::WEB);
    $this->withoutEvents();

    $product = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $product->refresh();

    expect($product->name_fr)->toBe('French Product Name');
    expect($product->description_fr)->toBe('French product description');
});

it('does not update product name if there is only created history and it is blank in the import file', function () {
    $product = Product::factory()->create([
        'stock_id' => '300300',
        'name' => 'Third English Product Name',
        'name_fr' => 'Existing Third French Product Name',
        'description' => 'Third English product description',
        'description_fr' => 'Existing Third French product description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $product->refresh();

    expect($product->name_fr)->toBe('Existing Third French Product Name');
});

it('does not update product description if there is only created history and it is blank in the import file', function () {
    $product = Product::factory()->create([
        'stock_id' => '300300',
        'name' => 'Third English Product Name',
        'name_fr' => 'Existing Third French Product Name',
        'description' => 'Third English product description',
        'description_fr' => 'Existing Third French product description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $product->refresh();

    expect($product->description_fr)->toBe('Existing Third French product description');
});

it('does not update product name if there is updated history and it is blank in the import file', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $product = Product::factory()->create([
        'stock_id' => '300300',
        'name' => 'Third English Product Name',
        'name_fr' => 'Existing Third French Product Name',
        'description' => 'Third English product description',
        'description_fr' => 'Existing Third French product description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->travel(5)->days();
    $product->name_fr = 'Manual Existing Third French Product Name';
    $product->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $product->refresh();

    expect($product->name_fr)->toBe('Manual Existing Third French Product Name');
});

it('does not update product description if there is updated history and it is blank in the import file', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $product = Product::factory()->create([
        'stock_id' => '300300',
        'name' => 'Third English Product Name',
        'name_fr' => 'Existing Third French Product Name',
        'description' => 'Third English product description',
        'description_fr' => 'Existing Third French product description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->travel(5)->days();
    $product->description_fr = 'Manual Existing Third French product description';
    $product->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $product->refresh();

    expect($product->description_fr)->toBe('Manual Existing Third French product description');
});

it('does not update product name if there is no history and it is blank in the import file', function () {
    Config::set('accountant.contexts', Context::WEB);
    $this->withoutEvents();

    $product = Product::factory()->create([
        'stock_id' => '300300',
        'name' => 'Third English Product Name',
        'name_fr' => 'Existing Third French Product Name',
        'description' => 'Third English product description',
        'description_fr' => 'Existing Third French product description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $product->refresh();

    expect($product->name_fr)->toBe('Existing Third French Product Name');
});

it('does not update product description if there is no history and it is blank in the import file', function () {
    Config::set('accountant.contexts', Context::WEB);
    $this->withoutEvents();

    $product = Product::factory()->create([
        'stock_id' => '300300',
        'name' => 'Third English Product Name',
        'name_fr' => 'Existing Third French Product Name',
        'description' => 'Third English product description',
        'description_fr' => 'Existing Third French product description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $product->refresh();

    expect($product->description_fr)->toBe('Existing Third French product description');
});

it('does not update product name when product name modified after the cut off date', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $product = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->travelTo($cutoffDate);
    $product->name_fr = 'Manual French Product Name';
    $product->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $product->refresh();

    expect($product->name_fr)->toBe('Manual French Product Name');
    expect($product->description_fr)->toBe('French product description');
});

it('does not update product description when product description modified after the cut off date', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $product = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->travelTo($cutoffDate);
    $product->description_fr = 'Manual French product description';
    $product->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $product->refresh();

    expect($product->name_fr)->toBe('French Product Name');
    expect($product->description_fr)->toBe('Manual French product description');
});

it('does not update a product when name and description have been modified after the cut off date', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $product = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->travelTo($cutoffDate);
    $product->name_fr = 'Manual French Product Name';
    $product->description_fr = 'Manual French product description';
    $product->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $product->refresh();

    expect($product->name_fr)->toBe('Manual French Product Name');
    expect($product->description_fr)->toBe('Manual French product description');
});

/**
 * Brand tests
 */
it('can update a brand description', function () {
    $brand = Brand::factory()->create([
        'name' => 'English Brand Name',
        'name_fr' => null,
        'description' => 'English brand description',
        'description_fr' => null,
    ]);

    runCommandWithPopulatedFile();

    $brand->refresh();

    expect($brand->description_fr)->toBe('French brand description');
});

it('updates a brand description if there are no historical ledger records', function () {
    Config::set('accountant.contexts', Context::WEB);
    $this->withoutEvents();

    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $brand = Brand::factory()->create([
        'name' => 'English Brand Name',
        'description' => 'English brand description',
        'description_fr' => null,
    ]);

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $brand->refresh();

    expect($brand->description_fr)->toBe('French brand description');
});

it('does not update brand name', function () {
    $brand = Brand::factory()->create([
        'name' => 'Third English Brand Name',
        'name_fr' => 'Existing Third French Brand Name',
        'description' => 'Third English brand description',
        'description_fr' => 'Existing Third French brand description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $brand->refresh();

    expect($brand->name_fr)->toBe('Existing Third French Brand Name');
});

it('does not update brand description if there is only created history and it is blank in the import file', function () {
    $brand = Brand::factory()->create([
        'name' => 'Third English Brand Name',
        'name_fr' => 'Existing Third French Brand Name',
        'description' => 'Third English brand description',
        'description_fr' => 'Existing Third French brand description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $brand->refresh();

    expect($brand->description_fr)->toBe('Existing Third French brand description');
});

it('does not update brand description if there is updated history and it is blank in the import file', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $brand = Brand::factory()->create([
        'name' => 'Third English Brand Name',
        'name_fr' => 'Existing Third French Brand Name',
        'description' => 'Third English brand description',
        'description_fr' => 'Existing Third French brand description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $this->travel(5)->days();
    $brand->description_fr = 'Manual Existing Third French brand description';
    $brand->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $brand->refresh();

    expect($brand->description_fr)->toBe('Manual Existing Third French brand description');
});

it('does not update brand description if there is no history and it is blank in the import file', function () {
    Config::set('accountant.contexts', Context::WEB);
    $this->withoutEvents();

    $brand = Brand::factory()->create([
        'name' => 'Third English Brand Name',
        'name_fr' => 'Existing Third French Brand Name',
        'description' => 'Third English brand description',
        'description_fr' => 'Existing Third French brand description',
        'state' => SignoffStateHelper::INITIAL,
    ]);

    runCommandWithPopulatedFile();

    $brand->refresh();

    expect($brand->description_fr)->toBe('Existing Third French brand description');
});

it('does not update brand name even if brand name modified before cutoff date', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $brand = Brand::factory()->create([
        'name' => 'English Brand Name',
        'name_fr' => 'Manual French Brand Name',
        'description' => 'English brand description',
        'description_fr' => null,
    ]);

    $this->travelTo($cutoffDate);
    $brand->name_fr = 'Manual French Brand Name';
    $brand->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $brand->refresh();

    expect($brand->name_fr)->toBe('Manual French Brand Name');
});

it('does not update brand description when modified after the cut off date', function () {
    $cutoffDate = CarbonImmutable::parse('2021-06-23');

    $this->travelTo($cutoffDate->subDays(30));
    $brand = Brand::factory()->create([
        'name' => 'English Brand Name',
        'name_fr' => null,
        'description' => 'English brand description',
        'description_fr' => null,
    ]);

    $this->travelTo($cutoffDate);
    $brand->description_fr = 'Manual French brand description';
    $brand->save();

    $this->travelBack();

    runCommandWithPopulatedFile($cutoffDate->format('Y-m-d'));

    $brand->refresh();

    expect($brand->description_fr)->toBe('Manual French brand description');
});

/**
 * Catalogue Category tests
 */
it('can update a catalogue category', function () {
    $catalogueCategory1 = CatalogueCategory::factory()->create([
        'name' => 'English Catalogue Category Name',
        'name_fr' => null,
    ]);

    $catalogueCategory2 = CatalogueCategory::factory()->create([
        'name' => 'English Catalogue Category Name',
        'name_fr' => null,
    ]);

    $catalogueCategory3 = CatalogueCategory::factory()->create([
        'name' => 'English Catalogue Category Name',
        'name_fr' => 'Manual French Catalogue Name',
    ]);

    runCommandWithPopulatedFile();

    $catalogueCategory1->refresh();
    $catalogueCategory2->refresh();
    $catalogueCategory3->refresh();

    expect($catalogueCategory1->name_fr)->toBe('French Catalogue Category Name');
    expect($catalogueCategory2->name_fr)->toBe('French Catalogue Category Name');
    expect($catalogueCategory3->name_fr)->toBe('French Catalogue Category Name');
});

/**
 * Helper functions
 */
function buildWorld()
{
    $product1 = Product::factory()->create([
        'stock_id' => '100100',
        'name' => 'English Product Name',
        'name_fr' => null,
        'description' => 'English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $brand1 = Brand::factory()->create([
        'name' => 'English Brand Name',
        'name_fr' => null,
        'description' => 'English brand description',
        'description_fr' => null,
    ]);

    $catalogueCategory1 = CatalogueCategory::factory()->create([
        'name' => 'English Catalogue Category Name',
        'name_fr' => null,
    ]);

    // create the second row of data
    $product2 = Product::factory()->create([
        'stock_id' => '200200',
        'name' => 'Second English Product Name',
        'name_fr' => null,
        'description' => 'Second English product description',
        'description_fr' => null,
        'state' => SignoffStateHelper::INITIAL,
    ]);

    $brand2 = Brand::factory()->create([
        'name' => 'Second English Brand Name',
        'name_fr' => null,
        'description' => 'Second English brand description',
        'description_fr' => null,
    ]);

    $catalogueCategory2 = CatalogueCategory::factory()->create([
        'name' => 'Second English Catalogue Category Name',
        'name_fr' => null,
    ]);

    return [
        'firstProduct' => $product1,
        'firstBrand' => $brand1,
        'firstCatalogueCategory' => $catalogueCategory1,
        'secondProduct' => $product2,
        'secondBrand' => $brand2,
        'secondCatalogueCategory' => $catalogueCategory2,
    ];
}

function runCommandWithPopulatedFile($cutoffDate = null)
{
    return artisan('update:french-translations', [
        'translationFile' => 'tests/files/update_french_translations_test.xlsx',
        '--cutoff' => $cutoffDate ?? now()->format('Y-m-d'),
    ])->expectsConfirmation('Are you sure you want to update the translations?', 'yes')
        ->expectsConfirmation('Are you REALLY sure you want to update the translations?  THIS CAN NOT BE UNDONE!', 'yes');
}
