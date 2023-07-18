<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class ProductRelistingNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        return [
            'Product Name' => $this->signoff->proposed->name,
            'Brand' => $this->signoff->proposed->brand->name,
            'Vendor Stock Code' => $this->signoff->proposed->brand_stock_id,
            'Purity Stock Id' => $this->signoff->proposed->stock_id,
        ];
    }

    protected function resolveDynamicProperties(): string
    {
        return $this->signoff->proposed->brand->name . ' - ' . $this->signoff->proposed->stock_id;
    }
}
