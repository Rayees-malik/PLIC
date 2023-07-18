<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Actions\Api\V1\RetrievesProducts;
use App\Http\Resources\ProductResource;

class ProductController
{
    public function index(RetrievesProducts $retrieveProducts)
    {
        return ProductResource::collection($retrieveProducts());
    }
}
