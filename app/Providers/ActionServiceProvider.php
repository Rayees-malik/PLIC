<?php

namespace App\Providers;

use App\Actions\Api\V1\RetrieveProducts;
use App\Contracts\Actions\Api\V1\RetrievesProducts;
use Illuminate\Support\ServiceProvider;

class ActionServiceProvider extends ServiceProvider
{
    public array $bindings = [
        RetrievesProducts::class => RetrieveProducts::class,
    ];
}
