<?php

use App\Models\Retailer;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('throws an unauthorized exception if user is not logged in when call scopeWithAccess', function () {
    Retailer::withAccess()->get();
})->throws(HttpException::class);
