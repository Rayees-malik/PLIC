<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class BrandDiscoRequestNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Brand Name' => $this->signoff->proposed->name,
        ];
    }
}
