<?php

use App\Models\Brand;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('throws an unauthorized exception if user is not logged in when call scopeWithAccess', function () {
    Brand::withAccess()->get();
})->throws(HttpException::class);
