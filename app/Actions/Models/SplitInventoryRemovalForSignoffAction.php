<?php

namespace App\Actions\Models;

use App\Actions\Signoffs\SubmitSignoffAction;
use App\DataTransferObjects\SignoffSubmitData;
use App\Models\InventoryRemoval;
use App\Models\Signoff;
use Illuminate\Support\Arr;

class SplitInventoryRemovalForSignoffAction
{
    public function __construct(private SubmitSignoffAction $submitSignoffAction)
    {
    }

    public function execute(InventoryRemoval $inventoryRemoval)
    {
        // Reload
        $originalRemoval = InventoryRemoval::allStates()->withEagerLoadedRelations()->find($inventoryRemoval->id);

        $brandNames = [];
        $warehouses = [];
        foreach ($originalRemoval->lineItems as $lineItem) {
            $warehouse = $lineItem->warehouse;
            $brandId = $lineItem->product->brand_id;
            $brandNames[$brandId] = $lineItem->product->brand->name;

            $items = Arr::get($warehouses, "{$warehouse}.{$brandId}", []);
            $items[] = $lineItem;
            Arr::set($warehouses, "{$warehouse}.{$brandId}", $items);
        }

        // Prevent cloning LineItems
        $cloneableRelations = $originalRemoval->cloneable_relations;
        $originalRemoval->cloneable_relations = null;

        // Split Base on Warehouse and Brand
        $removals = [];
        foreach ($warehouses as $warehouse => $brands) {
            foreach ($brands as $brandId => $lineItems) {
                $removal = null;
                if (! $removals) {
                    $removal = $originalRemoval;
                } else {
                    $removal = $originalRemoval->duplicate();
                    Signoff::startNewSignoff($removal);
                }

                $brandName = $brandNames[$brandId];
                $removal->name = "{$warehouse} - {$brandName}";

                $hasPickup = false;
                foreach ($lineItems as $lineItem) {
                    $lineItem->inventory_removal_id = $removal->id;
                    $lineItem->save();

                    $hasPickup = $hasPickup || $lineItem->vendor_pickup;
                }

                $removal->vendor_pickup = $hasPickup;
                $removal->save();
                $removals[] = $removal;
            }
        }

        // Submit For Signoff
        foreach ($removals as $removal) {
            $removal->cloneable_relations = $cloneableRelations;
            $inventoryRemoval = $removal->submitSignoff();

            $signoffSubmitData = new SignoffSubmitData([
                'action' => 'submit',
                'user' => auth()->user(),
            ]);

            $signoffSubmitData = $signoffSubmitData->forSignoff($inventoryRemoval->signoff->refresh());
            $this->submitSignoffAction->execute($signoffSubmitData);
        }
    }
}
