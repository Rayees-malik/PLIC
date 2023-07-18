<?php

namespace App\Http\Controllers;

use App\Http\Requests\Promos\DiscoPromoLineItemFormRequest;
use App\Models\DiscoPromoLineItem;
use App\Models\Product;
use DateTime;
use Illuminate\Support\Arr;

class DiscoPromoController extends Controller
{
    public function edit()
    {
        // TODO: Update with new sorting from Promo
        $brands = Product::inStockDisco()->with([
            'discoPromo',
            'as400Pricing' => function ($query) {
                $query->select('product_id', 'wholesale_price', 'po_price');
            },
            'uom' => function ($query) {
                $query->select('id', 'unit');
            },
            'brand' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->select('id', 'brand_id', 'name', 'stock_id', 'upc', 'size', 'uom_id')
            ->get()
            ->sort(function ($a, $b) {
                $aLineItem = $a->discoPromo;
                $bLineItem = $b->discoPromo;

                if ($aLineItem == $bLineItem) {
                    return strcasecmp($a->name, $b->name);
                }

                return $aLineItem > $bLineItem ? -1 : 1;
            }) // custom sort (discount pricing then name)
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE) // then by brand
            ->groupBy('brand.name');

        return view('discopromos.edit', [
            'brands' => $brands,
        ]);
    }

    public function update(DiscoPromoLineItemFormRequest $request)
    {
        $startTime = new DateTime;
        $formData = $request->partialValidated()->validated;
        $upserts = [];
        foreach (Arr::get($formData, 'products', []) as $productId) {
            $vendorDiscount = Arr::get($formData, "brand_discount.{$productId}", null);
            $plDiscount = Arr::get($formData, "pl_discount.{$productId}", null);

            if (! empty($vendorDiscount) || ! empty($plDiscount)) {
                $upserts[] = [
                    'product_id' => $productId,
                    'brand_discount' => $vendorDiscount,
                    'pl_discount' => $plDiscount,
                ];
            }
        }
        DiscoPromoLineItem::upsert($upserts, ['product_id'], ['brand_discount', 'pl_discount']);

        $oldLineItems = DiscoPromoLineItem::where('updated_at', '<', $startTime)->select('id')->get();
        foreach ($oldLineItems as $oldLineItem) {
            $oldLineItem->delete();
        }

        flash('Successfully saved disco promos.', 'success');

        return redirect()->route('discopromos.edit');
    }

    public function view()
    {
        // TODO: Update with new sorting from Promo
        $brands = Product::inStockDisco()->with([
            'discoPromo',
            'as400Pricing' => function ($query) {
                $query->select('product_id', 'wholesale_price', 'po_price');
            },
            'uom' => function ($query) {
                $query->select('id', 'unit');
            },
            'brand' => function ($query) {
                $query->select('id', 'name');
            },
        ])
            ->select('id', 'brand_id', 'name', 'stock_id', 'upc', 'size', 'uom_id')
            ->get()
            ->sort(function ($a, $b) {
                $aLineItem = $a->discoPromo;
                $bLineItem = $b->discoPromo;

                if ($aLineItem == $bLineItem) {
                    return strcasecmp($a->name, $b->name);
                }

                return $aLineItem > $bLineItem ? -1 : 1;
            }) // custom sort (discount pricing then name)
            ->sortBy('brand.name', SORT_NATURAL | SORT_FLAG_CASE) // then by brand
            ->groupBy('brand.name');

        return view('discopromos.view', [
            'brands' => $brands,
        ]);
    }
}
