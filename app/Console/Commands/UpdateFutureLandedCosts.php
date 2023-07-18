<?php

namespace App\Console\Commands;

use App\Models\FutureLandedCost;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateFutureLandedCosts extends Command
{
    protected $signature = 'update:futurelandedcosts';

    protected $description = 'Set the new Landed Cost for products when a new future landed cost comes due';

    public function handle()
    {
        $futureLandedCosts = FutureLandedCost::with([
            'product' => function ($query) {
                $query->select('id');
            },
        ])
            ->where('change_date', '<=', Carbon::now())
            ->orderBy('change_date', 'asc')
            ->get();

        foreach ($futureLandedCosts as $futureLandedCost) {
            $futureLandedCost->product->landed_cost = $futureLandedCost->landed_cost;
            $futureLandedCost->product->save();

            $futureLandedCost->delete();
        }
    }
}
