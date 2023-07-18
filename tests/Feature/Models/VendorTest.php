<?php

use App\Models\Vendor;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('throws an unauthorized exception if user is not logged in when call scopeWithAccess', function () {
    Vendor::withAccess()->get();
})->throws(HttpException::class);
