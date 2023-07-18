<?php

use App\Models\InventoryRemoval;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('throws an unauthorized exception if user is not logged in when call scopeWithAccess', function () {
    InventoryRemoval::withAccess()->get();
})->throws(HttpException::class);
