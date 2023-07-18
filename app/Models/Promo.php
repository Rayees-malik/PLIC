<?php

namespace App\Models;

use App\Helpers\PromoHelper;
use App\Helpers\SignoffStateHelper;
use App\RecordableModel;
use App\Scopes\PromoScope;
use App\Traits\RequiresSignoff;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use YlsIdeas\FeatureFlags\Facades\Features;

class Promo extends RecordableModel
{
    use RequiresSignoff;
    use HasFactory;

    public $pivotOverrides = ['data' => 'promo_data'];

    public $formErrors = null;

    protected $guarded = ['id'];

    protected $with = ['period'];

    protected $casts = [
        'data' => 'json',
    ];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    protected $clone_exempt_attributes = [];

    protected $clear_on_clone = ['submitted_by'];

    protected $cloneable_relations = ['lineItems'];

    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new PromoScope);
    }

    public static function getLookupVariables()
    {
        return ['brands', 'brand', 'periods', 'categories', 'basePeriodId', 'promoConfig', 'ownerId', 'ownerRoutePrefix'];
    }

    public static function loadLookups($model = null, $owner = null)
    {
        if ($model) {
            $brands = null;
            $brand = $model->brand;
        } else {
            $brands = Brand::active()->withPending()->withAccess()->whereHas('products', function ($query) {
                $query->withPending();
            })->with(['currency' => function ($query) {
                $query->select('id', 'name', 'exchange_rate');
            }])->select('id', 'name', 'allow_oi', 'currency_id', 'default_pl_discount')->ordered()->get();
            $brand = $model->brand ?? $brands->first();
        }

        $periods = null;
        $categories = null;
        $basePromo = null;
        $basePeriodId = null;
        if ($brand) {
            if (! $model || $model->{$model->stateField()} == SignoffStateHelper::IN_PROGRESS) {
                if ($model && ! $owner && $model->period->owner_id) {
                    $owner = $model->period->owner()->select('id')->first();
                }

                $periods = PromoPeriod::active()
                    ->byOwner($owner)
                    ->whereDoesntHave('promos', function ($query) use ($brand) {
                        $query->withPending()->where('brand_id', $brand->id);
                    })
                    ->select('id', 'name', 'start_date', 'end_date', 'base_period_id', 'type')
                    ->ordered()
                    ->get();
            } else {
                $periods = PromoPeriod::where('id', $model->period_id)->get();
            }

            $selectedPeriod = $model->period ?? $periods->first();
            $basePeriodId = $selectedPeriod ? $selectedPeriod->base_period_id : null;
            $categories = static::getPromoProductsForBrand($brand->id, $selectedPeriod, $model);
        }

        $promoConfig = null;
        if ($model) {
            $promoConfig = $model->getPromoConfig();
        } elseif ($owner) {
            $promoConfig = Arr::get(Config::get('retailer-promos'), $owner->id);
        }

        $ownerId = $owner ? $owner->id : null;
        $ownerRoutePrefix = $owner ? $owner->routePrefix : null;

        $combined = [
            'brands' => $brands,
            'brand' => $brand,
            'periods' => $periods,
            'categories' => $categories,
            'basePeriodId' => $basePeriodId,
            'promoConfig' => $promoConfig,
            'ownerId' => $ownerId,
            'ownerRoutePrefix' => $ownerRoutePrefix,
        ];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function getPromoProductsForBrand($brandId, $period = null, $promo = null)
    {
        $products = Product::withPromoPricing([optional($period)->id, optional($period)->base_period_id], true, false, true, $promo)
            ->whereHas('as400StockData', function ($query) {
                $query->where('status', 'A');
            })
            ->with([
                'uom' => function ($query) {
                    $query->select('id', 'unit');
                },
                'catalogueCategory' => function ($query) {
                    $query->select('id', 'name');
                },
            ])
            ->where('brand_id', $brandId)
            ->whereHas('as400Pricing', function ($query) {
                $query->where('wholesale_price', '>', 0);
            })
            ->select(['id', 'brand_id', 'name', 'stock_id', 'upc', 'size', 'uom_id', 'catalogue_category_id'])
            ->withCount([
                'promoLineItems as onPromo' => function ($query) use ($period) {
                    $query->whereHas('promo', function ($query) use ($period) {
                        $query->where('period_id', optional($period)->id);
                    });
                },
                'promoLineItems as onBasePromo' => function ($query) use ($period) {
                    $query->whereHas('promo', function ($query) use ($period) {
                        $query->where('period_id', optional($period)->base_period_id);
                    });
                },
            ])
            ->get()
            ->sortBy('catalogueCategory.name')
            ->sortByDesc('onBasePromo')
            ->sortByDesc('onPromo')
            ->groupBy('catalogueCategory.name');

        return $products;
    }

    public function scopeWithEagerLoadedRelations($query, $event = null)
    {
        return $query->with([
            'period',
            'lineItems',
            'brand' => function ($query) {
                $query->with(['currency' => function ($query) {
                    $query->select('id', 'name', 'exchange_rate');
                }])->select('id', 'name', 'allow_oi', 'currency_id', 'default_pl_discount');
            },
        ]);
    }

    public function getPromoConfig()
    {
        return $this->period->owner_id ? Arr::get(Config::get('retailer-promos'), $this->period->owner_id) : null;
    }

    public function extraUpdates($request)
    {
        $errors = new \App\SteppedViewErrorBag;
        $errors->put('header', app(\App\Http\Requests\Promos\PromoFormRequest::class)->partialValidated()->errors);

        $lineItemData = app(\App\Http\Requests\Promos\PromoLineItemFormRequest::class)->partialValidated();
        $errors->put('lineItems', $lineItemData->errors);

        $promoConfig = $this->getPromoConfig();

        $ownerData = null;
        $ownerFields = [];
        $ownerLineItemData = null;
        $ownerLineItemFields = [];
        if ($promoConfig) {
            $formRequestClass = Arr::get($promoConfig, 'formRequest');
            if ($formRequestClass && class_exists($formRequestClass)) {
                $ownerData = app($formRequestClass)->partialValidated();

                $errors->put('ownerHeader', $ownerData->errors);
                $ownerFields = Arr::get($promoConfig, 'promoFields');
            }

            $lineItemFormRequestClass = Arr::get($promoConfig, 'lineItemFormRequest');
            if ($lineItemFormRequestClass && class_exists($lineItemFormRequestClass)) {
                $ownerLineItemData = app($lineItemFormRequestClass)->partialValidated();
                $errors->put('ownerLineItems', $ownerLineItemData->errors);
                $ownerLineItemFields = Arr::get($promoConfig, 'lineItemFields');
            }
        }

        $ownerValues = [];
        foreach ($ownerFields as $field => $fieldConfig) {
            $ownerValues[$field] = Arr::get($ownerData->validated, "{$field}", null);
        }
        if (! empty($ownerValues)) {
            $this->data = $ownerValues;
            $this->save();
        }

        foreach (Arr::get($lineItemData->validated, 'products', []) as $productId) {
            $hasOwnerField = false;
            $ownerLineItemValues = [];
            foreach ($ownerLineItemFields as $field => $fieldConfig) {
                $fieldValue = Arr::get($ownerLineItemData->validated, "{$field}.{$productId}", null);
                if (! Arr::get($fieldConfig, 'saveIgnore') && ! empty($fieldValue)) {
                    $hasOwnerField = true;
                }

                $ownerLineItemValues[$field] = $fieldValue;
            }

            $oi = Arr::get($lineItemData->validated, "lineitem_oi.{$productId}", null);
            $brandDiscount = Arr::get($lineItemData->validated, "brand_discount.{$productId}", null);
            $plDiscount = Arr::get($lineItemData->validated, "pl_discount.{$productId}", null);

            if (is_null($brandDiscount)) {
                $plDiscount = null;
            } elseif ($brandDiscount == 0) {
                $plDiscount = $plDiscount ? preg_replace('/[^0-9.]/', '', $plDiscount) : null;
            } else {
                if (is_null($plDiscount) || $plDiscount == 0) {
                    $plDiscount = null;
                } else {
                    $plDiscount = $plDiscount ? preg_replace('/[^0-9.]/', '', $plDiscount) : null;
                }
            }

            // $brandDiscount = $brandDiscount ? preg_replace('/[^0-9.]/', '', $brandDiscount) : null;
            // $plDiscount = $plDiscount ? preg_replace('/[^0-9.]/', '', $plDiscount) : null;

            $data = [
                'promo_id' => $this->id,
                'product_id' => $productId,
                'oi' => $oi,
                'brand_discount' => $brandDiscount,
                'pl_discount' => $plDiscount,
                'data' => $ownerLineItemValues,
            ];

            $lineItem = $this->lineItems->where('product_id', $productId)->first();

            if (! empty($brandDiscount) || ! empty($plDiscount) || $hasOwnerField) {
                if ($lineItem) {
                    $lineItem->update($data);
                } else {
                    $lineItem = new PromoLineItem;
                    $lineItem->fill($data);
                    $lineItem->save();
                }
            } elseif ($lineItem) {
                $lineItem->delete();
            }
        }

        if (! $errors) {
            $this->checkForLineDrive();
        }

        $this->formErrors = $errors;
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

            $periodName = '';
            if ($properties['period_id'] > 0) {
                $period = $periods->find($properties['period_id']);
                if (! $period) {
                    $period = PromoPeriod::select('id', 'name')->find($properties['period_id']);
                }

                $periodName = optional($period)->name;
            }

            $extra = [
                'owner_id' => $ownerId,
                'dollar_discount' => $properties['dollar_discount'] == 1 ? 'Dollar' : 'Percentage',
                'oi' => $properties['oi'] == '1' ? 'Yes' : 'No',
                'period_id' => $periodName,
            ];

            // handle BelongsToMany relations
            if ($event == 'created') {
                $extra = array_merge($extra, $this->getRelationsExtra());
            }
        }

        return $extra;
    }

    public function checkForLineDrive()
    {
        $products = Product::withPromoPricing([$this->period_id], true)->where('brand_id', $this->brand_id)->get();
        $discountData = PromoHelper::getAllDiscounts($products, $this->period);

        if (! $discountData || count($discountData) == 0) {
            return false;
        }

        $isLineDrive = $discountData[0]['line_drive'];
        if ($isLineDrive != $this->line_drive) {
            $this->line_drive = $isLineDrive;
            $this->save();
        }

        return $this->line_drive;
    }

    public function getCustomFieldPivotType($field, $ownerId = null)
    {
        $promoConfig = $ownerId ? Arr::get(Config::get('retailer-promos'), $ownerId) : null;

        return Arr::get($promoConfig, "promoFields.{$field}.pivotType") ?? 'pivot_data';
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        // Allow Costing to see all promos
        if ($user && $user->can('signoff.product.promo.finance')) {
            return $query;
        }

        return $query->where(function ($query) use ($user) {
            $query->where(function ($query) use ($user) {
                // Regular Promos
                $query->whereHas('period', function ($query) {
                    $query->whereNull('owner_id');
                })->whereHas('brand', function ($query) use ($user) {
                    $query->signoffFilter($user);
                });
            })->orWhere(function ($query) use ($user) {
                // Owned Promos
                if ($user && $user->can('signoff.retailer.promo')) {
                    $query->whereHas('period', function ($query) use ($user) {
                        $query->whereHasMorph('owner', '*', function ($query) use ($user) {
                            $query->signoffFilter($user);
                        });
                    });
                } else {
                    $query->whereRaw('1 = 2');
                }
            });
        });
    }

    public function nextStep($step, $signoff)
    {
        if ($step == 1) {
            // Next Step Finance
            if ($this->period->owner_id) {
                // skip Finance step
                return $step + 2;
            }
        }

        return ++$step;
    }

    public function setDefaultPLDiscount()
    {
        $defaultPLDiscount = $this->brand->default_pl_discount;
        if (! $defaultPLDiscount) {
            return;
        }

        $this->load('lineItems');
        foreach ($this->lineItems as $lineItem) {
            if ($lineItem->pl_discount || ! $lineItem->brand_discount) {
                continue;
            }

            $lineItem->pl_discount = $defaultPLDiscount;
            $lineItem->save();
        }
    }

    public function getUnsubmitNotificationUsers()
    {
        $ids = [$this->brand->purchasing_specialist_id, $this->brand->vendor_relations_specialist_id];
        $users = User::whereHas('roles.abilities', function ($query) {
            $query->where('name', 'promo.notify.unsubmit');
        })->orWhereIn('id', $ids)->get();
    }

    public function getCanUnsubmitPendingAttribute()
    {
        return $this->period->active;
    }

    public function getCanUnsubmitApprovedAttribute()
    {
        return $this->period->active;
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PromoPeriod::class, 'period_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'promo_line_items', 'promo_id', 'product_id')
            ->allStates()
            ->withPivot('brand_discount', 'pl_discount', 'data')
            ->using(\App\Pivots\JsonDataPivot::class);
    }

    public function lineItems(): HasMany
    {
        return $this->hasMany(PromoLineItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\User::class, 'submitted_by', 'id')->withTrashed();
    }
}
