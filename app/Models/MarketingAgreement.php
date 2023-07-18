<?php

namespace App\Models;

use App\Helpers\FileUploadHelper;
use App\Http\Requests\MarketingAgreements\MarketingAgreementFormRequest;
use App\Http\Requests\MarketingAgreements\MarketingAgreementLineItemFormRequest;
use App\Models\AS400\AS400Customer;
use App\RecordableModel;
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

class MarketingAgreement extends RecordableModel implements HasMedia
{
    use RequiresSignoff;
    use InteractsWithMedia;
    use Orderable;
    use HasFactory;

    const ORDER_BY = ['start_date' => 'desc']; // TODO

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

    protected $cloneable_relations = ['lineItems', 'media'];

    protected $eager_relations = ['user', 'sendTo', 'lineItems', 'media'];

    public static function getLookupVariables()
    {
        return ['customers', 'brands', 'sendToUsers', 'lineItemBrands', 'duplicateInvoice'];
    }

    public static function loadLookups($model = null)
    {
        $brands = Brand::withAccess()->active()->ordered()->get();
        $customers = AS400Customer::ordered()->get();

        $sendToUsers = User::where(function ($query) use ($model) {
            $query->whereIs('sales-manager') // TODO: Change to ability
                ->where('id', '<>', auth()->id()); // Prevent a user from selecting themselves

            if ($model) {
                // Ensure existing user is always selectable even if they are no longer a sales-manager
                $query->orWhere('id', $model->send_to);
            }
        })->select('id', 'name')->ordered()->get();

        $lineItemBrands = [];
        $duplicateInvoice = false;
        if ($model) {
            foreach ($model->lineItems as $lineItem) {
                if (! array_key_exists($lineItem->brand_id, $lineItemBrands)) {
                    $lineItemBrands[$lineItem->brand_id] = $lineItem->brand->name;
                }
            }
            asort($lineItemBrands);

            if ($model->retailer_invoice) {
                $duplicate = MarketingAgreement::withPending()
                    ->where([
                        'retailer_invoice' => $model->retailer_invoice,
                        'account' => $model->account,
                        'account_other' => $model->account_other,
                    ])
                    ->whereNotIn('id', [$model->signoff->proposed_id, $model->signoff->initial_id])
                    ->select('id')
                    ->first();

                $duplicateInvoice = $duplicate ? true : false;
            }
        }

        $combined = [
            'brands' => $brands,
            'customers' => $customers,
            'sendToUsers' => $sendToUsers,
            'lineItemBrands' => $lineItemBrands,
            'duplicateInvoice' => $duplicateInvoice,
        ];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function modifyFormData($formData, $model = null)
    {
        if (! $model || $model->account != $formData['account']) {
            if ($formData['account'] == 'Other') {
                $formData['name'] = empty($formData['account_other']) ? 'Other' : $formData['account_other'];
            } else {
                $customer = AS400Customer::where('customer_number', $formData['account'])->select('name')->first();
                $formData['name'] = $customer ? "{$customer->name} (#{$formData['account']})" : "Marketing Agreement (#{$formData['account']})";
            }
        }

        if (! $model) {
            $formData['submitted_by'] = auth()->id();
        }

        return $formData;
    }

    public function extraUpdates($request)
    {
        $errors = new \App\SteppedViewErrorBag;
        $errors->put('header', app(MarketingAgreementFormRequest::class)->partialValidated()->errors);

        $lineItemData = app(MarketingAgreementLineItemFormRequest::class)->partialValidated();
        $errors->put('lineItems', $lineItemData->errors);

        $deletedItems = array_filter(explode(',', $request->deleted_items));
        foreach (Arr::get($lineItemData->validated, 'lineitem_id', []) as $index => $id) {
            $brandId = Arr::get($lineItemData->validated, "brand_id.{$index}", null);
            $activity = Arr::get($lineItemData->validated, "activity.{$index}", null);
            $promoDates = Arr::get($lineItemData->validated, "promo_dates.{$index}", null);
            $cost = Arr::get($lineItemData->validated, "cost.{$index}", null);
            $mcbAmount = Arr::get($lineItemData->validated, "mcb_amount.{$index}", null);

            if (! $brandId) {
                continue;
            }

            $data = [
                'marketing_agreement_id' => $this->id,
                'brand_id' => $brandId,
                'activity' => $activity,
                'promo_dates' => $promoDates,
                'cost' => $cost,
                'mcb_amount' => $mcbAmount,
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
                $lineItem = new MarketingAgreementLineItem;
                $lineItem->fill($data);
                $lineItem->save();
            }
        }

        foreach ($deletedItems as $deletedId) {
            $lineItem = MarketingAgreementLineItem::where('marketing_agreement_id', $this->id)
                ->where(function ($query) use ($deletedId) {
                    $query->where('id', $deletedId)->orWhere('cloned_from_id', $deletedId);
                })
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

    public function supplyExtra(string $event, array $properties, ?\Altek\Accountant\Contracts\Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            if (Features::accessible('remove-session-dependency')) {
                extract(self::loadLookups($this));
            } else {
                extract(Session::get(static::getSessionRelationsKey()));
            }

            $extra = [
                'send_to' => $properties['send_to'] > 0 ? $sendToUsers->find($properties['send_to'])->name : '',
            ];

            if ($properties['account'] != 'Other') {
                $customer = $customers->where('customer_number', $properties['account'])->first();
                if ($customer) {
                    $extra['account'] = "{$customer->name} (#{$properties['account']})";
                }
            }

            if ($event == 'created') {
                $extra = array_merge($extra, $this->getRelationsExtra());
            }
        }

        return $extra;
    }

    public function getRoutePrefixAttribute()
    {
        return 'marketingagreements';
    }

    public function getIdentifierAttribute()
    {
        $timestamp = $this->created_at->format('ymd');
        $id = str_pad($this->cloned_from_id ?? $this->id, 5, '0', STR_PAD_LEFT);

        return "{$timestamp}{$id}";
    }

    public function getDisplayNameAttribute()
    {
        return "{$this->name} [{$this->retailer_invoice}]";
    }

    public function getCanUnsubmitPendingAttribute()
    {
        return true;
    }

    public function getAccountNameAndNumberAttribute()
    {
        if ($this->account == 'OTHER') {
            return $this->name . ' (' . $this->account_other . ')';
        }

        return $this->name . ' (#' . $this->account . ')';
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        if ($user) {
            $query->where('send_to', $user->id);
        } else {
            $query->whereRaw('send_to = users.id');
        }
        $query->orWhere('signoffs.step', '<>', '1');
    }

    public function calcSubtotal()
    {
        $subtotal = 0;
        foreach ($this->lineItems as $item) {
            $subtotal += $item->cost;
        }

        return $subtotal;
    }

    public function calcTax()
    {
        $subtotal = $this->calcSubtotal();
        $taxRate = $this->tax_rate ?? 13;

        $tax = $subtotal * ($taxRate / 100);

        return $tax;
    }

    public function calcTotal()
    {
        $subtotal = $this->calcSubtotal();
        $tax = $this->calcTax();

        $total = $subtotal + $tax;

        return $total;
    }

    public function prevStep($step, $signoff)
    {
        // Always go back to submitter
        return 0;
    }

    public function getSummaryArray($signoff)
    {
        return [
            'Signoff Comments' => optional($signoff->responses->last())->comment,
            'Submitted By' => $this->user->name,
            'Approved By' => optional($signoff->responses->last())->user->name ?? null,
            'Account Number' => $this->account ?? $this->account_other,
            'Retailer Invoice #' => $this->retailer_invoice,
            'Comments' => $this->comment,
            'Sub Total' => number_format($this->calcSubtotal(), 2),
            'Tax Rate' => number_format($this->calcTax(), 2),
            'Total' => number_format($this->calcTotal(), 2),
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by', 'id')->withTrashed();
    }

    public function sendTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'send_to', 'id')->withTrashed();
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(MarketingAgreementLineItem::class);
    }

    public function as400Customer(): BelongsTo
    {
        return $this->belongsTo(AS400Customer::class, 'account', 'customer_number');
    }
}
