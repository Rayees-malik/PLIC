<?php

use App\Exports\PromoSpecialsExport;
use App\Models\AS400\AS400Pricing;
use App\Models\AS400\AS400StockData;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Promo;
use App\Models\PromoLineItem;
use App\Models\PromoPeriod;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

it('has a view', function () {
    expect(view()->exists('exports.forms.promospecials'))->toBe(true);
});

it('must have a period to export', function () {
    disableDownload();

    $this->signIn()->assign('admin');

    $this->post(route('exports.export', ['name' => 'promospecials']), [
        'period_id' => '',
    ])->assertSessionHasErrors(['period_id']);
});

it('excludes AS/400 discontinued products even when still PLIC active', function () {
    disableDownload();

    $brand = Brand::factory()->create();

    $promoPeriod = PromoPeriod::factory()->startsInFuture()->create();
    $promo = Promo::factory()
        ->for($promoPeriod, 'period')
        ->for($brand)
        ->approved()
        ->create();

    $activeProduct = Product::factory()
        ->approved()
        ->active()
        ->for($brand)
        ->has(AS400StockData::factory()->active())
        ->has(AS400Pricing::factory())
        ->has(PromoLineItem::factory()->for($promo)->state(['brand_discount' => 0.5]))
        ->create([
            'name' => 'Active Product',
        ]);

    $discontinuedProduct = Product::factory()
        ->approved()
        ->active()
        ->for($brand)
        ->has(AS400StockData::factory()->discontinued())
        ->has(AS400Pricing::factory())
        ->has(PromoLineItem::factory()->for($promo)->state(['brand_discount' => 0.75]))
        ->create([
            'name' => 'Discontinued Product',
        ]);

    $productSheet = createExport(
        routeName: 'promospecials',
        exportClass: PromoSpecialsExport::class,
        params: [
            'period_id' => $promoPeriod->id,
        ])->getSpreadsheet()->getSheetByName('By Product');

    expect($productSheet->getHighestDataRow())->toBe(3);
    expect(getCellValue(4, 3, $productSheet))->toBe($activeProduct->stock_id);
    expect(getCellValue(4, 4, $productSheet))->toBeNull();
});
