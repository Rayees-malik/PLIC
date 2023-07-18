<?php

namespace App\Traits;

use App\Models\Brand;
use App\Models\Product;
use Exception;

trait PriceAdjustable
{
    public function scopeForPriceAdjustment($query)
    {
        if ($this instanceof Product) {
            return $query->withAccess()
                ->with([
                    'uom' => function ($query) {
                        $query->select('id', 'unit');
                    },
                    'brand' => function ($query) {
                        $query->select('id', 'name', 'vendor_id');
                    },
                    'brand.vendor' => function ($query) {
                        $query->select('id', 'who_to_mcb');
                    },
                ])
                ->ordered()
                ->select('id', 'stock_id', 'name', 'name_fr', 'packaging_language', 'uom_id', 'size', 'upc', 'brand_id');
        } elseif ($this instanceof Brand) {
            return $query->withAccess()
                ->with([
                    'vendor' => function ($query) {
                        $query->select('id', 'who_to_mcb');
                    },
                ])
                ->ordered()
                ->select('id', 'name', 'brand_number', 'vendor_id', 'category_code');
        }

        throw new Exception(get_class($this) . ' is not configured to be PriceAdjustable.');
    }

    public function getPAFStockId()
    {
        if ($this instanceof Product) {
            return $this->stock_id;
        } elseif ($this instanceof Brand) {
            return substr($this->brand_number, 1) . '*';
        }

        throw new Exception(get_class($this) . ' is not configured to be PriceAdjustable.');
    }

    public function getPAFUPC()
    {
        if ($this instanceof Product) {
            return $this->upc;
        } elseif ($this instanceof Brand) {
            return '';
        }

        throw new Exception(get_class($this) . ' is not configured to be PriceAdjustable.');
    }

    public function getPAFBrand()
    {
        if ($this instanceof Product) {
            return $this->brand->name;
        } elseif ($this instanceof Brand) {
            return $this->name;
        }

        throw new Exception(get_class($this) . ' is not configured to be PriceAdjustable.');
    }

    public function getPAFDescription()
    {
        if ($this instanceof Product) {
            return "{$this->getName()} ({$this->getSizeWithUnits()})";
        } elseif ($this instanceof Brand) {
            return "{$this->name} Line Drive";
        }

        throw new Exception(get_class($this) . ' is not configured to be PriceAdjustable.');
    }

    public function getPAFWhoToMCB()
    {
        if ($this instanceof Product) {
            return $this->brand->vendor->who_to_mcb ?? $this->brand->name;
        } elseif ($this instanceof Brand) {
            return $this->vendor->who_to_mcb ?? $this->name;
        }

        throw new Exception(get_class($this) . ' is not configured to be PriceAdjustable.');
    }

    public function getPriceAdjustmentData()
    {
        return [
            'morph_id' => $this->id,
            'morph_type' => get_class($this),
            'stock_id' => $this->getPAFStockId(),
            'upc' => $this->getPAFUPC(),
            'brand' => $this->getPAFBrand(),
            'description' => $this->getPAFDescription(),
            'who_to_mcb' => $this->getPAFWhoToMCB(),
        ];
    }
}
