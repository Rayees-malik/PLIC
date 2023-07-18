<?php

namespace App\Traits;

trait HasPivotValue
{
    public function getPivotType()
    {
        return property_exists($this, 'pivotChangeType') ? $this->pivotChangeType : 'pivot';
    }

    public function getPivotValue($isSet)
    {
        if ($isSet) {
            return $this->name;
        }

        return null;
    }
}
