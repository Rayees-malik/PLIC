<?php

namespace App\Models;

use Altek\Accountant\Contracts\Identifiable;
use App\RecordableModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryRemovalLineItem extends RecordableModel
{
    use HasFactory;

    public $pivotOverrides = [
        'product_id' => 'lineitem',
        'quantity' => 'lineitem',
        'full_mcb' => 'lineitem',
        'cost' => 'lineitem',
        'reserve' => 'lineitem',
        'vendor_pickup' => 'lineitem',
        'expiry' => 'lineitem',
        'warehouse' => 'lineitem',
        'reason' => 'lineitem',
    ];

    protected $guarded = ['id'];

    // Pull session from base InventoryRemoval model
    public static function getSessionRelationsKey()
    {
        return InventoryRemoval::getSessionRelationsKey();
    }

    public function supplyExtra(string $event, array $properties, ?Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            $extra = [
                'full_mcb' => $properties['full_mcb'] ? 'Yes' : 'No',
                'reserve' => $properties['reserve'] ? 'Yes' : 'No',
                'vendor_pickup' => $properties['vendor_pickup'] ? 'Yes' : 'No',
            ];
        }

        return $extra;
    }

    public function getSummaryArray()
    {
        return [
            'Stock ID' => $this->product->stock_id,
            'Product Name' => $this->product->name,
            'Company Name' => $this->product->brand->name,
            'Cost' => $this->cost,
            'Adj. Quantity' => $this->quantity,
            'Full MCB' => $this->full_mcb,
            'Reserve' => $this->reserve,
            'Vendor Pickup' => $this->vendor_pickup,
            'Expiry' => $this->expiry,
            'Reason' => $this->reason,
        ];
    }

    public function inventoryRemoval(): BelongsTo
    {
        return $this->belongsTo(InventoryRemoval::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class)->withPending();
    }
}
