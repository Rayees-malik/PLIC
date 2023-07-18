<?php

use App\Models\AS400\AS400Pricing;
use App\Models\AS400\AS400StockData;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Promo;
use App\Models\PromoLineItem;
use App\Models\PromoPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('only shows active products with wholesale price greater than 0', function () {
    //## Arrange
    // create a discontinued brand
    $brand = Brand::factory()->active()->create();

    // create a promo with line item for the brand in the future
    $promo = Promo::factory()
        ->for($brand, 'brand')
        ->for(PromoPeriod::factory()->startsInFuture(), 'period')
        ->create();

    // add a promo line item for a discontinued product with wholesale price greater than 0
    $discontinuedProductWithPrice = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->discontinued())
        ->has(AS400Pricing::factory()->state(fn () => ['wholesale_price' => 10]))
        ->discontinued()
        ->create(['stock_id' => 'DISCONTINUED-WITH-PRICE']);

    PromoLineItem::factory()
        ->for($discontinuedProductWithPrice, 'product')
        ->for($promo, 'promo')
        ->create();

    // add a promo line item for a discontinued product with wholesale price greater than 0
    $discontinuedProductWithoutPrice = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->discontinued())
        ->has(AS400Pricing::factory()->state(fn () => ['wholesale_price' => 0]))
        ->discontinued()
        ->create(['stock_id' => 'DISCONTINUED-WITHOUT-PRICE']);

    PromoLineItem::factory()
        ->for($discontinuedProductWithoutPrice, 'product')
        ->for($promo, 'promo')
        ->create();

    // add a promo line item for an active product with wholesale price greater than 0
    $activeProductWithPrice = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->active())
        ->has(AS400Pricing::factory()->state(fn () => ['wholesale_price' => 25]))
        ->active()
        ->create(['stock_id' => 'ACTIVE-WITH-PRICE']);

    PromoLineItem::factory()
        ->for($activeProductWithPrice, 'product')
        ->for($promo, 'promo')
        ->create();

    // add a promo line item for an active product with wholesale price equal to 0
    $activeProductWithoutPrice = Product::factory()
        ->for($brand)
        ->has(AS400StockData::factory()->active())
        ->has(AS400Pricing::factory()->state(fn () => ['wholesale_price' => 0]))
        ->active()
        ->create(['stock_id' => 'ACTIVE-WITHOUT-PRICE']);

    PromoLineItem::factory()
        ->for($activeProductWithoutPrice, 'product')
        ->for($promo, 'promo')
        ->create();

    $this->signIn('admin');

    $response = $this->get('promos/' . $promo->fresh()->id);

    $response->assertSee('ACTIVE-WITH-PRICE');
    $response->assertDontSee('DISCONTINUED-WITH-PRICE');
    $response->assertDontSee('DISCONTINUED-WITHOUT-PRICE');
    $response->assertDontSee('ACTIVE-WITHOUT-PRICE');
});
