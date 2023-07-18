<?php

namespace App\Models;

use App\Helpers\SignoffStateHelper;
use App\Http\Requests\InventoryRemovals\InventoryRemovalFormRequest;
use App\Http\Requests\InventoryRemovals\InventoryRemovalLineItemFormRequest;
use App\Http\Requests\InventoryRemovals\InventoryRemovalLineItemWarehouseFormRequest;
use App\RecordableModel;
use App\SteppedViewErrorBag;
use App\Traits\Orderable;
use App\Traits\RequiresSignoff;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\MessageBag;

class InventoryRemoval extends RecordableModel
{
    use RequiresSignoff;
    use Orderable;
    use HasFactory;

    const ORDER_BY = ['id' => 'desc']; // TODO

    const LOW_VALUE_REMOVAL = 100;

    const HIGH_VALUE_REMOVAL = 500;

    public $pivotOverrides = [
        'vendor_pickup' => 'ignore',
    ];

    public $cloneable_relations = ['lineItems'];

    public $formErrors = null;

    protected $guarded = ['id'];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    protected $eager_relations = ['user', 'lineItems'];

    public static function modifyFormData($formData, $model = null)
    {
        if (! $model) {
            $formData['submitted_by'] = auth()->id();
        }

        if (! $model || $model->state == SignoffStateHelper::IN_PROGRESS) {
            $warehouses = [];
            $lineItemData = app(InventoryRemovalLineItemFormRequest::class)->partialValidated();
            foreach (Arr::get($lineItemData->validated, 'warehouse', []) as $warehouse) {
                $warehouses[] = str_pad($warehouse, 2, '0', STR_PAD_LEFT);
            }

            $warehouses = array_unique(array_filter($warehouses));
            sort($warehouses);
            $namePrefix = count($warehouses) ? implode('/', $warehouses) . ' - ' : '';
            $formData['name'] = "{$namePrefix}New Inventory Removal";
        }

        return $formData;
    }

    public function scopeWithEagerLoadedRelations($query, $event = null)
    {
        return $query->with([
            'user',
            'lineItems' => function ($query) {
                $query->with(['product' => function ($query) {
                    $query->with(['brand' => function ($query) {
                        $query->select('id', 'name');
                    }])->select('id', 'name', 'stock_id', 'brand_id');
                }]);
            },
        ]);
    }

    public function extraUpdates($request)
    {
        if ($request->signoff_form && $this->signoff->step != 3) {
            return;
        }

        $errors = new SteppedViewErrorBag;
        $lineItemData = null;
        if ($request->signoff_form && $this->signoff->step == 3) {
            $lineItemData = app(InventoryRemovalLineItemWarehouseFormRequest::class)->partialValidated();
            $errors->put('lineItems', $lineItemData->errors);
        } else {
            $errors->put('header', app(InventoryRemovalFormRequest::class)->partialValidated()->errors);
            $lineItemData = app(InventoryRemovalLineItemFormRequest::class)->partialValidated();
            $errors->put('lineItems', $lineItemData->errors);
        }

        $hasPickup = false;
        $deletedItems = $request->signoff_form ? [] : array_filter(explode(',', $request->deleted_items));
        foreach (Arr::get($lineItemData->validated, 'lineitem_id', []) as $index => $id) {
            $quantity = Arr::get($lineItemData->validated, "quantity.{$index}", null);
            if ($request->signoff_form && $this->signoff->step == 3) {
                $lineItem = $id ? $this->lineItems->filter(function ($item) use ($id) {
                    return $item->id == $id || $item->cloned_from_id == $id;
                })->first() : null;

                if ($lineItem) {
                    $lineItem->update(['quantity' => $quantity]);
                }

                continue;
            }

            $fullMCB = Arr::get($lineItemData->validated, "full_mcb.{$index}", false);
            $reserve = Arr::get($lineItemData->validated, "reserve.{$index}", false);
            $reason = Arr::get($lineItemData->validated, "reason.{$index}", null);
            $vendorPickup = Arr::get($lineItemData->validated, "vendor_pickup.{$index}", null);
            $notes = Arr::get($lineItemData->validated, "notes.{$index}", null);

            $hasPickup = $hasPickup || $vendorPickup;

            $data = [
                'inventory_removal_id' => $this->id,
                'quantity' => $quantity,
                'full_mcb' => $fullMCB,
                'reserve' => $reserve,
                'vendor_pickup' => $vendorPickup,
                'reason' => $reason,
                'notes' => $notes,
            ];

            $lineItem = $id ? $this->lineItems->filter(function ($item) use ($id) {
                return $item->id == $id || $item->cloned_from_id == $id;
            })->first() : null;

            if ($lineItem) {
                $lineItem->update($data);

                if ($lineItem->cloned_from_id && in_array($lineItem->cloned_from_id, $deletedItems)) {
                    $lineItem->delete();
                }
            } else {
                // Only editable on creation
                $productId = Arr::get($lineItemData->validated, "product_id.{$index}", null);
                $average_landed_cost = Arr::get($lineItemData->validated, "average_landed_cost.{$index}", null);
                $cost = Arr::get($lineItemData->validated, "cost.{$index}", null);
                $expiry = Arr::get($lineItemData->validated, "expiry.{$index}", null);
                $warehouse = Arr::get($lineItemData->validated, "warehouse.{$index}", null);

                if (! $cost) {
                    continue;
                }

                $data['product_id'] = $productId;
                $data['average_landed_cost'] = $average_landed_cost;
                $data['cost'] = $cost;
                $data['expiry'] = $expiry;
                $data['warehouse'] = str_pad($warehouse, 2, '0', STR_PAD_LEFT);

                $lineItem = new InventoryRemovalLineItem;
                $lineItem->fill($data);
                $lineItem->save();
            }
        }
        if (Arr::get($lineItemData->validated, 'vendor_pickup') && $this->vendor_pickup != $hasPickup) {
            $this->vendor_pickup = $hasPickup;
            $this->save();
        }

        if (! $request->signoff_form) {
            foreach ($deletedItems as $deletedId) {
                $lineItem = InventoryRemovalLineItem::where('inventory_removal_id', $this->id)
                    ->where(function ($query) use ($deletedId) {
                        $query->where('id', $deletedId)->orWhere('cloned_from_id', $deletedId);
                    })
                    ->first();

                if ($lineItem) {
                    $lineItem->delete();
                }
            }
        }

        if ($this->lineItems->count() === 0) {
            $flshMessage = new MessageBag;
            $flshMessage->add('flash', 'At least one product must be included.');
            $errors->put('flash', $flshMessage);
        }

        $this->formErrors = $errors;
    }

    public function calculateTotal($skipMCB = false)
    {
        $lineItems = $this->lineItems()->with([
            'product' => function ($query) {
                $query->with(['brand' => function ($query) {
                    $query->with('as400Consignment')->select('id');
                }])->select('id', 'brand_id', 'stock_id');
            },
        ])->get();

        $totalValue = 0;
        foreach ($lineItems as $lineItem) {
            $consignment = false;
            if ($skipMCB) {
                $consignment = strtolower(substr($lineItem->product->stock_id, -1)) == 'c' || optional($lineItem->product->brand->as400Consignment)->consignment;
            }

            if (! $skipMCB || (! $consignment && ! $lineItem->full_mcb)) {
                $totalValue += round($lineItem->quantity * $lineItem->cost, 2);
            }
        }

        return $totalValue;
    }

    public function calculateAverageLandedTotal($skipMCB = false)
    {
        $lineItems = $this->lineItems()->with([
            'product' => function ($query) {
                $query->with(['brand' => function ($query) {
                    $query->with('as400Consignment')->select('id');
                }])->select('id', 'brand_id', 'stock_id');
            },
        ])->get();

        $totalValue = 0;
        foreach ($lineItems as $lineItem) {
            $consignment = false;
            if ($skipMCB) {
                $consignment = strtolower(substr($lineItem->product->stock_id, -1)) == 'c' || optional($lineItem->product->brand->as400Consignment)->consignment;
            }

            if (! $skipMCB || (! $consignment && ! $lineItem->full_mcb)) {
                $totalValue += round($lineItem->quantity * $lineItem->average_landed_cost, 2);
            }
        }

        return $totalValue;
    }

    public function getDisplayNameAttribute()
    {
        $id = $this->cloned_from_id ?? $this->id;
        $pickup = $this->vendor_pickup ? ' PU' : '';

        return "{$this->name} [#{$id}{$pickup}]";
    }

    public function getRoutePrefixAttribute()
    {
        return 'inventoryremovals';
    }

    public function scopeWithAccess($query, $user = null)
    {
        if (! $user) {
            $user = auth()->user();
        }

        abort_if(! $user, 401, 'You must be logged in to access this resource.');

        if ($user->can('signoff.inventory-removals.management') || $user->can('signoff.inventory-removals.finance')) {
            return $query;
        }

        if ($user->can('signoff.inventory-removals.qc')) {
            $linkedWarehouses = [];
            if ($user->can('warehouse.01')) {
                $linkedWarehouses[] = '01';
                $linkedWarehouses[] = '50';
            }
            if ($user->can('warehouse.04')) {
                $linkedWarehouses[] = '04';
                $linkedWarehouses[] = '40';
            }
            if ($user->can('warehouse.08')) {
                $linkedWarehouses[] = '08';
                $linkedWarehouses[] = '80';
            }
            if ($user->can('warehouse.09')) {
                $linkedWarehouses[] = '09';
                $linkedWarehouses[] = '90';
            }

            return $query->whereHas('lineItems', function ($query) use ($linkedWarehouses) {
                $query->whereIn('warehouse', $linkedWarehouses);
            })->orWhere('submitted_by', $user->id);
        }

        return $query->where('submitted_by', $user->id);
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        if ($user) {
            $query->where(function ($query) use ($user) {
                $query->withAccess($user)->where('signoffs.step', 3);
            })->orWhere('signoffs.step', '<>', 3);
        } else {
            $query->where(function ($query) {
                $query->where(function ($query) {
                    $query->whereHas('lineItems', function ($query) {
                        $query->where(function ($query) {
                            // TODO: integrate this into the query to prevent extra pulls
                            $warehouseUsers = User::whereHas('roles.abilities', function ($query) {
                                $query->where('name', 'warehouse.01');
                            })->pluck('id')->toArray();

                            $query->whereIn('warehouse', ['01', '50'])
                                ->whereIn('users.id', $warehouseUsers);
                        })->orWhere(function ($query) {
                            // TODO: integrate this into the query to prevent extra pulls
                            $warehouseUsers = User::whereHas('roles.abilities', function ($query) {
                                $query->where('name', 'warehouse.04');
                            })->pluck('id')->toArray();

                            $query->whereIn('warehouse', ['04', '40'])
                                ->whereIn('users.id', $warehouseUsers);
                        })->orWhere(function ($query) {
                            // TODO: integrate this into the query to prevent extra pulls
                            $warehouseUsers = User::whereHas('roles.abilities', function ($query) {
                                $query->where('name', 'warehouse.08');
                            })->pluck('id')->toArray();

                            $query->whereIn('warehouse', ['08', '80'])
                                ->whereIn('users.id', $warehouseUsers);
                        })->orWhere(function ($query) {
                            // TODO: integrate this into the query to prevent extra pulls
                            $warehouseUsers = User::whereHas('roles.abilities', function ($query) {
                                $query->where('name', 'warehouse.09');
                            })->pluck('id')->toArray();

                            $query->whereIn('warehouse', ['09', '90'])
                                ->whereIn('users.id', $warehouseUsers);
                        });
                    });
                })->where('signoffs.step', 3);
                // Only route for warehouse on step 3
            })->orWhere('signoffs.step', '<>', 3);
        }
    }

    public function nextStep($step, $signoff)
    {
        if ($step == 0) {
            // Next Step Management
            $totalValue = $this->calculateTotal(true);

            if ($totalValue < InventoryRemoval::LOW_VALUE_REMOVAL) {
                // Go right to step 3
                return 3;
            } elseif ($totalValue < InventoryRemoval::HIGH_VALUE_REMOVAL) {
                // Only need 1 management signoff
                return 2;
            } else {
                // Two management signoffs
                return 1;
            }
        }

        if ($step == 1) {
            // skip past the second management step
            return 3;
        }

        return ++$step;
    }

    public function prevStep($step, $signoff)
    {
        // Always go back to submitter
        return 0;
    }

    public function getSummaryArray($signoff)
    {
        $comments = [];
        foreach ($signoff->responses as $response) {
            if (! $response->archived && $response->comment) {
                $comments[] = "{$response->user->name}: {$response->comment}";
            }
        }

        $lineItem = $this->lineItems()->with(['product' => function ($query) {
            $query->with(['brand' => function ($query) {
                $query->select('id', 'name');
            }])->select('id', 'brand_id');
        }])->first();

        $summary = $lineItem ? [
            'Brand' => $lineItem->product->brand->name,
            'Warehouse' => $lineItem->warehouse,
        ] : [];

        if (count($comments)) {
            $summary['Signoff Comments'] = implode('<br>', $comments);
        }

        return $summary;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id')->withTrashed();
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(InventoryRemovalLineItem::class);
    }
}
