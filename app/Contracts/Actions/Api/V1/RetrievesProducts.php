<?php

namespace App\Contracts\Actions\Api\V1;

use Illuminate\Support\Collection;

interface RetrievesProducts
{
    public function __invoke(): Collection;
}
