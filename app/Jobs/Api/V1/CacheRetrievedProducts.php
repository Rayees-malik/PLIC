<?php

namespace App\Jobs\Api\V1;

use App\Actions\Api\V1\RetrieveProducts;
use App\Contracts\Actions\Api\V1\RetrievesProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class CacheRetrievedProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::forget(RetrieveProducts::class);
        $command = resolve(RetrievesProducts::class);
        $command();
    }
}
