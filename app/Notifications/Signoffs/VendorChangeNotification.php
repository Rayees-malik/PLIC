<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class VendorChangeNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Vendor Name' => $this->signoff->proposed->name,
        ];
    }
}
