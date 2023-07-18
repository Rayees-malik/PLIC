<?php

use App\Jobs\Api\V1\CacheRetrievedProducts;
use App\Models\AS400\AS400BrandInvoice;
use App\Models\AS400\AS400BrandOpenAP;
use App\Models\AS400\AS400BrandPOReceived;
use App\Models\AS400\AS400Consignment;
use App\Models\AS400\AS400Customer;
use App\Models\AS400\AS400CustomerGroup;
use App\Models\AS400\AS400Freight;
use App\Models\AS400\AS400Margin;
use App\Models\AS400\AS400Pricing;
use App\Models\AS400\AS400SpecialPricing;
use App\Models\AS400\AS400StockData;
use App\Models\AS400\AS400Supersedes;
use App\Models\AS400\AS400UpcomingPriceChange;
use App\Models\AS400\AS400WarehouseStock;
use App\Models\AS400\AS400ZeusRetailer;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

use function Pest\Laravel\artisan;

uses(DatabaseTransactions::class);

it('runs command to cache products data after the AS400 migration completes', function () {
    Bus::fake();
    Event::fake();

    $schedule = resolve(Schedule::class);

    $event = collect($schedule->events())->first(function ($event) {
        return Str::contains($event->command, 'as400:migrate');
    });

    $event->callAfterCallbacks($this->app);
    Bus::assertDispatched(CacheRetrievedProducts::class);
});

it('can be run only for a single table', function () {
    Product::factory()->for(Brand::factory())->count(3)->create();

    mockAS400();

    artisan('as400:migrate', ['table' => ['special_pricing']])
        ->expectsOutput('Importing special_pricing [ ' . AS400SpecialPricing::class . ' ]...')
        ->doesntExpectOutput('Importing customers [ ' . AS400Customer::class . ' ]...');
});

it('can be run only for specific tables', function () {
    Product::factory()->for(Brand::factory())->count(3)->create();

    mockAS400();

    artisan('as400:migrate', ['table' => ['special_pricing', 'upcoming_price_changes']])
        ->expectsOutput('Importing special_pricing [ ' . AS400SpecialPricing::class . ' ]...')
        ->expectsOutput('Importing upcoming_price_changes [ ' . AS400UpcomingPriceChange::class . ' ]...')
        ->doesntExpectOutput('Importing customers [ ' . AS400Customer::class . ' ]...');
});

it('runs for all tables if no argument passed', function () {
    Product::factory()->for(Brand::factory())->count(3)->create();

    mockAS400();

    artisan('as400:migrate')
        ->expectsOutput('Importing brand_invoice [ ' . AS400BrandInvoice::class . ' ]...')
        ->expectsOutput('Importing brand_open_ap [ ' . AS400BrandOpenAP::class . ' ]...')
        ->expectsOutput('Importing brand_po_received [ ' . AS400BrandPOReceived::class . ' ]...')
        ->expectsOutput('Importing customers [ ' . AS400Customer::class . ' ]...')
        ->expectsOutput('Importing customer_groups [ ' . AS400CustomerGroup::class . ' ]...')
        ->expectsOutput('Importing consignment [ ' . AS400Consignment::class . ' ]...')
        ->expectsOutput('Importing freight [ ' . AS400Freight::class . ' ]...')
        ->expectsOutput('Importing margin [ ' . AS400Margin::class . ' ]...')
        ->expectsOutput('Importing pricing [ ' . AS400Pricing::class . ' ]...')
        ->expectsOutput('Importing special_pricing [ ' . AS400SpecialPricing::class . ' ]...')
        ->expectsOutput('Importing stock_data [ ' . AS400StockData::class . ' ]...')
        ->expectsOutput('Importing supercededs [ ' . AS400Supersedes::class . ' ]...')
        ->expectsOutput('Importing upcoming_price_changes [ ' . AS400UpcomingPriceChange::class . ' ]...')
        ->expectsOutput('Importing warehouse_stock [ ' . AS400WarehouseStock::class . ' ]...')
        ->expectsOutput('Importing zeus_retailers [ ' . AS400ZeusRetailer::class . ' ]...');
});
