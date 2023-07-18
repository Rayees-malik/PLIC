<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class PromoChangeNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Promo Name' => $this->signoff->proposed->name,
        ];
    }
}
