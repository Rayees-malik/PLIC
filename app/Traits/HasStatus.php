<?php

namespace App\Traits;

use App\Helpers\StatusHelper;
use App\Scopes\StatusScope;

trait HasStatus
{
    public static function bootHasStatus()
    {
        static::addGlobalScope(new StatusScope);
    }

    public function getStatus()
    {
        return StatusHelper::toString($this->status);
    }
}
