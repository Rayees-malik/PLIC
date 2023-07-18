<?php

namespace App\Traits;

trait Orderable
{
    public function scopeOrdered($query)
    {
        $orderBys = defined('static::ORDER_BY') ? static::ORDER_BY : ['name' => 'asc'];
        foreach ($orderBys as $orderby => $direction) {
            $query = $query->orderBy($orderby, $direction);
        }
    }
}
