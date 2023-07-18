<?php

namespace App\Notifications\Signoffs;

use App\Notifications\SignoffNotification;

class ProductListingNotification extends SignoffNotification
{
    public function resolveAttributes(): array
    {
        $data = [
            'Product Name' => $this->signoff->proposed->name,
            'Brand' => $this->signoff->proposed->brand->name,
            'Vendor Stock Code' => $this->signoff->proposed->brand_stock_id,
        ];

        if ($this->signoff->proposed->stock_id) {
            $data['Purity Stock Id'] = $this->signoff->proposed->stock_id;
        }

        $data = array_merge($data, [
            'Availability Date' => $this->signoff->proposed->available_ship_date,
            'UPC Code' => $this->signoff->proposed->upc,
            'Wholesale Price' => $this->signoff->proposed->wholesale_price,
            'Purity Cost' => $this->signoff->proposed->unit_cost,
        ]);

        return $data;
    }

    protected function resolveDynamicProperties(): string
    {
        $subject = $this->signoff->proposed->brand->name . ' - ' . $this->signoff->proposed->name;

        if ($this->signoff->proposed->stock_id) {
            $subject .= ' [' . $this->signoff->proposed->stock_id . ']';
        }

        return $subject;
    }
}
