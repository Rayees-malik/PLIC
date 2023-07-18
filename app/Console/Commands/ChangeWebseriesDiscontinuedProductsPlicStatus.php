<?php

namespace App\Console\Commands;

use App\Helpers\SignoffStateHelper;
use App\Helpers\StatusHelper;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ChangeWebseriesDiscontinuedProductsPlicStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:plic-product-disco-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the PLIC product status to discontinued if the AS/400 product status is discontinued (\'D\')';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Product::with('productDelistRequests')
            ->where('state', 1)
            // ->whereIn('stock_id', ['690297', '690085'])
            ->where('status', StatusHelper::ACTIVE)
            ->whereHas('as400StockData', function ($query) {
                $query->where('status', 'D');
            })
            ->whereRelation('productDelistRequests', 'state', SignoffStateHelper::INITIAL)
            ->get();

        $this->info("Found {$products->count()} products to update");

        DB::transaction(function () use ($products) {
            foreach ($products as $product) {
                $this->info("Updating product {$product->id} [{$product->stock_id}] ({$product->name})");

                $product->status = StatusHelper::DISCONTINUED;
                $product->save();
            }
        });

        return 0;
    }
}
