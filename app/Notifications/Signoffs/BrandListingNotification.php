<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class BrandListingNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Brand Name' => $this->signoff->proposed->name,
            'Brand #' => $this->signoff->proposed->brand_number,
            'Contract Exclusive' => $this->signoff->proposed->contract_exclusive == 1 ? 'Yes' : 'No',
            'Nutrition House' => $this->signoff->proposed->nutrition_house == '1' ? 'Yes' : 'No',
            'Health First' => $this->signoff->proposed->health_first == '1' ? 'Yes' : 'No',
            'Consignment' => $this->signoff->proposed->vendor->consignment == '1' ? 'Yes' : 'No',
        ];
    }
}
