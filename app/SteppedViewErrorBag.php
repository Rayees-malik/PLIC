<?php

namespace App;

use Illuminate\Support\ViewErrorBag;

class SteppedViewErrorBag extends ViewErrorBag
{
    public function allBagsEmpty()
    {
        foreach ($this->bags as $bag) {
            if (! $bag->isEmpty()) {
                return false;
            }
        }

        return true;
    }
}
