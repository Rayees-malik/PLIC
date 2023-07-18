<?php

namespace App\Models;

use Altek\Accountant\Contracts\Identifiable;
use App\Helpers\FileUploadHelper;
use App\Http\Requests\PricingAdjustments\PricingAdjustmentFormRequest;
use App\Http\Requests\PricingAdjustments\PricingAdjustmentLineItemFormRequest;
use App\Models\AS400\AS400Customer;
use App\Models\AS400\AS400CustomerGroup;
use App\RecordableModel;
use App\SteppedViewErrorBag;
use App\Traits\Orderable;
use App\Traits\RequiresSignoff;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use YlsIdeas\FeatureFlags\Facades\Features;

class PricingAdjustment extends RecordableModel implements HasMedia
{
    use RequiresSignoff,
        InteractsWithMedia,
        Orderable, HasFactory;

    const ORDER_BY = ['start_date' => 'desc'];

    public $pivotOverrides = ['accounts' => 'concat'];

    public $formErrors = null;

    protected $guarded = ['id'];

    protected $casts = [
        'accounts' => 'json',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    protected $cloneable_relations = ['lineItems', 'media'];

    protected $eager_relations = ['user', 'lineItems', 'media'];

    public static function getLookupVariables()
    {
        return ['customers'];
    }

    public static function loadLookups($model = null)
    {
        $groups = array_flip(AS400CustomerGroup::pluck('code')->toArray());
        $customers = AS400Customer::ordered()->get()->groupBy(function ($customer) use ($groups) {
            return array_key_exists($customer->price_code, $groups) ? $customer->price_code : '';
        });

        $combined = ['customers' => $customers];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function modifyFormData($formData, $model = null)
    {
        $formDataAccounts = Arr::get($formData, 'accounts', []);
        if (! $model || $model->accounts != $formDataAccounts) {
            $customers = array_unique(AS400Customer::whereIn('customer_number', $formDataAccounts)->pluck('name')->toArray());
            if (count($customers) > 3) {
                $customers = array_slice($customers, 0, 3);
                $customers[] = 'and more';
            }

            $formData['name'] = implode(', ', $customers);
        }

        if (! $model) {
            $formData['submitted_by'] = auth()->id();
        }

        return $formData;
    }

    public function extraUpdates($request)
    {
        $errors = new SteppedViewErrorBag;
        $errors->put('header', app(PricingAdjustmentFormRequest::class)->partialValidated()->errors);

        $lineItemData = app(PricingAdjustmentLineItemFormRequest::class)->partialValidated();

        $errors->put('lineItems', $lineItemData->errors);

        $deletedItems = array_filter(explode(',', $request->deleted_items));
        foreach (Arr::get($lineItemData->validated, 'lineitem_id', []) as $index => $id) {
            $morphId = Arr::get($lineItemData->validated, "morph_id.{$index}", null);
            $morphType = Arr::get($lineItemData->validated, "morph_type.{$index}", null);
            $totalDiscount = Arr::get($lineItemData->validated, "total_discount.{$index}", null);
            $totalMCB = Arr::get($lineItemData->validated, "total_mcb.{$index}", null);
            $whoToMCB = Arr::get($lineItemData->validated, "who_to_mcb.{$index}", null);

            if (! $id && (! $morphId || ! $morphType)) {
                continue;
            }

            $data = [
                'pricing_adjustment_id' => $this->id,
                'item_id' => $morphId,
                'item_type' => $morphType,
                'total_discount' => $totalDiscount,
                'total_mcb' => $totalMCB,
                'who_to_mcb' => $whoToMCB,
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
                $lineItem = new PricingAdjustmentLineItem;
                $lineItem->fill($data);
                $lineItem->save();
            }
        }

        $deletedItems = array_filter(explode(',', $request->deleted_items));
        foreach ($deletedItems as $deletedId) {
            $lineItem = PricingAdjustmentLineItem::where('pricing_adjustment_id', $this->id)
                ->where('id', $deletedId)
                ->first();

            if ($lineItem) {
                $lineItem->delete();
            }
        }

        $this->formErrors = $errors;
    }

    public function uploadFiles($formData)
    {
        FileUploadHelper::storeFiles($formData, ['attachments' => 'uploads'], $this);
    }

    public function supplyExtra(string $event, array $properties, ?Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            $extra = [
                'ongoing' => $properties['ongoing'] == '1' ? 'Yes' : 'No',
                'dollar_discount' => $properties['dollar_discount'] == 1 ? 'Dollar' : 'Percentage',
                'dollar_mcb' => $properties['dollar_mcb'] == 1 ? 'Dollar' : 'Percentage',
                'bpp' => $properties['bpp'] == '1' ? 'Yes' : 'No',
                'shared_line' => $properties['shared_line'] == '1' ? 'Yes' : 'No',
                'accounts' => implode(', ', json_decode($properties['accounts'])),
            ];

            // handle BelongsToMany relations
            if ($event == 'created') {
                $extra = array_merge($extra, $this->getRelationsExtra());
            }
        }

        return $extra;
    }

    public function getDisplayNameAttribute()
    {
        $id = $this->cloned_from_id ?? $this->id;

        return "#{$id} - {$this->name} [{$this->start_date->toFormattedDateString()}]";
    }

    public function getRoutePrefixAttribute()
    {
        return 'pricingadjustments';
    }

    public function getCanUnsubmitPendingAttribute()
    {
        return true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id')->withTrashed();
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PricingAdjustmentLineItem::class);
    }
}
