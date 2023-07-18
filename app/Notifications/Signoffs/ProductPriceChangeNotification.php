<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class ProductPriceChangeNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        $data = [
            'Product Name' => $this->signoff->proposed->name,
            'Brand' => $this->signoff->proposed->brand->name,
            'Purity Stock Id' => $this->signoff->proposed->stock_id,
            'New Cost' => $this->signoff->proposed->unit_cost,
            'Effective Date' => $this->signoff->proposed->price_change_date->format('Y-m-d'),
        ];

        if ($this->signoff->step >= 4) {
            $data['New Wholesale Price'] = $this->signoff->proposed->wholesale_price;
        }

        return $data;
    }

    protected function resolveTextDetails()
    {
        return 'Other details beyond price may also have been updated.';
    }

    protected function resolveDynamicProperties(): string
    {
        return $this->signoff->proposed->brand->name . ' - ' . $this->signoff->proposed->name . ' [' . $this->signoff->proposed->stock_id . ']';
    }
}
