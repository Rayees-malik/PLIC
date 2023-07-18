<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Schema;
use YlsIdeas\FeatureFlags\Facades\Features;

trait BehindFeatureFlag
{
    public function isHidden()
    {
        if ($this->hidden) {
            return true;
        }
        if (($this->featureFlag && Schema::hasTable('features') && ! Features::accessible($this->featureFlag))) {
            $this->setHidden = true;

            return true;
        }

        return false;
    }
}
