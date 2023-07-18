<?php

namespace App\Exports\ListingForms;

use App\Models\Product;

class WholeFoodsCanadaListingForm extends SimpleListingForm
{
    const WHOLESALE_BRANDS = [
        // Antipodes
        35 => 0.84,
        // Kyolic
        366 => 0.75,
        // Pacifica
        549 => 1,
        // Quantum
        588 => 0.85,
    ];

    protected $template = 'templates/listingforms/whole_foods_canada.xlsx';

    protected $filename = 'whole_foods_canada_listingform.xlsx';

    protected $startingRow = 4;

    protected $startingColumn = 'B';

    protected $worksheetIndex = 3;

    protected $priceCode = 'WFM-COST';

    public function query($stockIds, $includeNonCatalogue = false)
    {
        return Product::catalogueActive($includeNonCatalogue)
            ->with([
                'certifications' => function ($query) {
                    $query->select('product_id', 'name');
                },
                'brand' => function ($query) {
                    return $query->with(['as400SpecialPricing' => function ($query) {
                        $query->byCode($this->priceCode)->forDate();
                    }])->select('id', 'name', 'vendor_id');
                },
                'brand.contacts',
                'brand.vendor.contacts',
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'average_landed_cost', 'taxable');
                },
                'as400SpecialPricing' => function ($query) {
                    $query->byCode($this->priceCode);
                },
                'category' => function ($query) {
                    $query->select('id', 'name');
                },
                'subcategory' => function ($query) {
                    $query->select('id', 'name');
                },
                'uom' => function ($query) {
                    $query->select('id', 'unit');
                },
            ])
            ->select(
                'id',
                'category_id',
                'subcategory_id',
                'upc',
                'name',
                'landed_cost',
                'stock_id',
                'uom_id',
                'size',
                'brand_id',
                'purity_sell_by_unit',
                'inner_units',
                'master_units',
            )
            ->whereIn('stock_id', $stockIds)
            ->get();
    }

    public function data($stockIds, $includeNonCatalogue)
    {
        $data = [];

        $products = $this->query($stockIds, $includeNonCatalogue);
        foreach ($products as $product) {
            $data = array_merge($data, $this->getRow($product));
        }

        return $data;
    }

    public function isBar($product)
    {
        if ($product->soldByCase) {
            return false;
        }

        if (optional($product->category)->name == 'Food & Beverage' || optional($product->category)->name = 'Supplements') {
            if (strpos(strtolower(optional($product->subcategory)->name), 'bar') !== false) {
                return true;
            }
        }

        return false;
    }

    public function getRow($product, $isBox = false)
    {
        $price = array_key_exists($product->brand_id, $this::WHOLESALE_BRANDS) ? round($product->getPrice(null, $this->priceCode) * $this::WHOLESALE_BRANDS[$product->brand_id], 2) : round($product->landed_cost * 1.113, 2);
        $certifications = $product->certifications->pluck('name')->toArray();

        $isBar = ! $isBox && $this->isBar($product);
        if ($isBox) {
            $upc = $product->inner_upc ?? $product->master_upc;
        } else {
            $upc = $product->upc;
        }

        if ($isBar) {
            $productName = "{$product->name} Bar";
        } elseif ($isBox) {
            $productName = "{$product->name} Box";
        } else {
            $productName = $product->name;
        }

        $brandContact = $product->brand->contacts()->where('role', '=', 'vendor')->first();
        $brandVendorContact = $product->brand->vendor->contacts()->where('role', '=', 'vendor')->first();

        if (! $brandContact && ! $brandVendorContact) {
            $brandContactName = null;
            $brandContactEmail = null;
            $brandContactTelephone = null;
        } else {
            $brandContactName = $brandContact->name ?? $brandVendorContact->name;
            $brandContactEmail = $brandContact->email ?? $brandVendorContact->email;
            $brandContactTelephone = $brandContact->phone ?? $brandVendorContact->phone;
        }

        $productData = [
            optional($product->category)->name,
            optional($product->subcategory)->name,
            substr($upc, 0, -1),
            substr($upc, -1),
            null,
            $product->brand->name,
            $productName,
            $isBar ? null : $product->stock_id,
            $isBox ? 1 : round($product->size, 2),
            $isBox ? 'CT' : optional($product->uom)->unit,
            $isBox ? 1 : $product->minimumSellBy,
            $price,
            null,
            null,
            null,
            null,
            'Purity Life',
            'michael.chapman@puritylife.com', // Distributor Contact Email
            $brandContactName, // Brand Contact Name
            $brandContactEmail, // Brand Contact Email
            $brandContactTelephone, // Direct Brand Phone
            null,
            null,
            null,
            null,
            null,
            in_array('Fair Trade', $certifications) ? 'Yes' : 'No',
            in_array('Gluten Free', $certifications) ? 'Yes' : 'No',
        ];

        if ($isBar) {
            return array_merge([$productData], $this->getRow($product, true));
        }

        return [$productData];
    }
}
