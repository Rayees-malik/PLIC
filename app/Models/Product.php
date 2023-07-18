<?php

namespace App\Models;

use App\Helpers\SignoffStateHelper;
use App\Media;
use App\Models\AS400\AS400Pricing;
use App\Models\AS400\AS400SpecialPricing;
use App\Models\AS400\AS400StockData;
use App\Models\AS400\AS400UpcomingPriceChange;
use App\Models\AS400\AS400WarehouseStock;
use App\Pivots\JsonDataPivot;
use App\RecordableModel;
use App\Traits\HasStatus;
use App\Traits\HasSteps;
use App\Traits\HasThumbnails;
use App\Traits\Orderable;
use App\Traits\PriceAdjustable;
use App\Traits\RequiresSignoff;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use stdClass;
use YlsIdeas\FeatureFlags\Facades\Features;

class Product extends RecordableModel implements HasMedia
{
    use HasFactory;
    use RequiresSignoff;
    use HasThumbnails;
    use Orderable;
    use HasSteps {
        HasSteps::stepErrors as baseStepErrors;
    }
    use HasStatus;
    use PriceAdjustable;

    const SELL_BY_UNITS = [
        1 => 'Single',
        2 => 'Inner',
        4 => 'Master',
    ];

    const PACKAGING_LANGUAGES = [
        'E' => 'English Only',
        'F' => 'French Only',
        'B' => 'Bilingual',
        'M' => 'Minimal French',
    ];

    const ORDER_BY = ['name' => 'asc', 'size' => 'asc'];

    public $pivotOverrides = ['retailer_sell_by_unit' => 'concat'];

    protected $guarded = ['id'];

    protected $public = 'products';

    protected $casts = ['price_change_date' => 'date'];

    protected $clone_exempt_attributes = ['landed_cost'];

    protected $clear_on_clone = [
        'stock_id', 'upc', 'inner_upc', 'master_upc', 'wholesale_price', 'unit_cost', 'extra_addon_percent',
        'price_change_reason', 'price_change_date', 'temp_edlp', 'temp_duty', 'submission_notes',
    ];

    protected $cloneable_relations = [
        'allergens', 'certifications', 'dimensions', 'innerDimensions',
        'masterDimensions', 'packagingMaterials', 'regulatoryInfo',
        'flags', 'media',
    ];

    protected $with = [
        'as400Pricing',
    ];

    protected $eager_relations = [
        'brand' => ['none' => ['withPending']],
        'brand.currency', 'brand.as400Freight', 'brand.as400Margin', 'allergens', 'certifications',
        'dimensions', 'innerDimensions', 'masterDimensions', 'packagingMaterials', 'regulatoryInfo',
        'flags', 'category', 'countryOrigin', 'countryShipped', 'media', 'catalogueCategory',
        'as400Pricing', 'as400PricingClone', 'as400StockData', 'as400StockDataClone', 'signoff',
    ];

    protected $recordableEvents = [
        'created',
        'updated',
        'restored',
        'deleted',
        'forceDeleted',
        'synced',
    ];

    public static function getLookupVariables()
    {
        return [
            'brand', 'brands', 'countries', 'categories', 'subcategories',
            'flags', 'allergens', 'certifications', 'uoms',
            'packagingMaterials', 'catalogueCategories',
        ];
    }

    public static function loadLookups($model = null)
    {
        $brands = Brand::withPending()->active()->withAccess()->select('id', 'name', 'brand_number')->ordered()->get();

        if ($model && $model->brand && ! $brands->contains('id', $model->brand_id)) {
            $brands->push($model->brand);
            $brands = $brands->sortBy('name');
        }

        $countries = Country::select('id', 'name')->ordered()->get();
        $categories = ProductCategory::select('id', 'name')->ordered()->get();
        $flags = ProductFlag::select('id', 'name')->ordered()->get();
        $allergens = Allergen::select('id', 'name')->ordered()->get();
        $certifications = Certification::select('id', 'name')->ordered()->get();
        $uoms = UnitOfMeasure::select('id', 'unit', 'description')->ordered()->get();
        $packagingMaterials = PackagingMaterial::select('id', 'name')->ordered()->get();

        $subcategories = static::loadSubcategories(optional($model)->category_id, $model);
        $catalogueCategories = static::loadCatalogueCategories(optional($model)->brand_id, $brands);
        $brand = static::loadSelectedBrand($model, $brands);

        $combined = [
            'brand' => $brand,
            'brands' => $brands,
            'countries' => $countries,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'flags' => $flags,
            'allergens' => $allergens,
            'certifications' => $certifications,
            'uoms' => $uoms,
            'packagingMaterials' => $packagingMaterials,
            'catalogueCategories' => $catalogueCategories,
        ];

        if (! Features::accessible('remove-session-dependency')) {
            Session::put(static::getSessionRelationsKey(), $combined);
        }

        return $combined;
    }

    public static function loadSubcategories($categoryId, $model)
    {
        if ($categoryId) {
            return ProductSubcategory::byCategory($categoryId, optional($model)->subcategory_id)->select('id', 'name', 'code')->ordered()->get();
        }

        return collect([]);
    }

    public static function loadCatalogueCategories($brand_id, $brands)
    {
        $brand = $brand_id ? $brands->where('id', $brand_id)->first() : $brands->first();
        if (! $brand) {
            return collect([]);
        }

        $categories = $brand->catalogueCategories()->select('id', 'name', 'name_fr')->get();
        $defaultCategories = Config::get('categories')['catalogueCategories'];

        foreach ($defaultCategories as $key => $category) {
            if (! $categories->contains('name', $category['name'])) {
                $cat = new CatalogueCategory;
                $cat->fill($category);
                $cat->id = $key;
                $categories->push($cat);
            }
        }

        return $categories->sortBy('name');
    }

    public static function loadSelectedBrand($model, $brands)
    {
        if ($model && $model->brand) {
            return $model->brand;
        }

        if (! (request()->brand_id ?? $brands->count())) {
            return;
        }

        return Brand::withPending()
            ->with(['currency', 'as400Freight', 'as400Margin'])
            ->select('id', 'name', 'currency_id')
            ->find(request()->brand_id ?? $brands->first()->id);
    }

    public static function stepperUpdate(\Illuminate\Http\Request $request, $submitting = false)
    {
        // Validate form sections and handle errors
        $productValidation = app(\App\Http\Requests\Products\ProductFormRequest::class)->partialValidated();
        $saveValidation = app(\App\Http\Requests\Products\ProductSaveRequiredFormRequest::class)->partialValidated();
        $relationsValidation = app(\App\Http\Requests\Products\ProductRelationsFormRequest::class)->partialValidated();
        $regulatoryValidation = app(\App\Http\Requests\Products\RegulatoryStepFormRequest::class)->partialValidated();

        $saveMessages = new \Illuminate\Support\MessageBag;
        foreach ($saveValidation->errors->all() as $message) {
            $saveMessages->add('general-flash', $message);
        }

        if (! $request->id && $saveMessages->isNotEmpty()) {
            $saved = false;
            $model = new Product;
            // Ensure the proper subcategories are loaded
            $model->category_id = Arr::get($productValidation->validated, 'category_id');

            $errors = $model->stepErrors();
            $errors->put('flash', $saveMessages);
        } else {
            $isDuplicate = false;
            $model = self::allStates()->withAccess()->withEagerLoadedRelations()->find($request->id);

            $oldLandedCost = $model ? $model->landed_cost : null;
            $oldUnitCost = $model ? optional($model->as400Pricing)->po_price : null;
            $oldWholesale = $model ? optional($model->as400Pricing)->wholesale_price : null;
            $extraAddonPercent = $model ? optional($model->as400Pricing)->extra_addon_percent : null;

            if (! $model) {
                $model = static::startSignoff();
            } elseif ($model->{$model->stateField()} == SignoffStateHelper::INITIAL) {
                $isDuplicate = true;
            }

            $model->catalogue_category_id = Arr::get($productValidation->validated, 'catalogue_category_id');
            $model->update($productValidation->validated);

            if ($isDuplicate) {
                $model = $model->getLastProposed();

                // Save old pricing values for use in change history
                $model->old_landed_cost = $oldLandedCost;
                $model->old_unit_cost = $oldUnitCost;
                $model->old_wholesale_price = $oldWholesale;
                $model->wholesale_price = $oldWholesale;
                $model->extra_addon_percent = $extraAddonPercent;
                $model->save();
            }

            // Handle Relationships
            // Dimensions
            $imperial = $request->get('measurement_system') == 'imperial';
            $cmModifier = $imperial ? 2.54 : 1;
            $kgModifier = $imperial ? 2.2046 : 1;
            foreach (['unit' => 'dimensions', 'inner' => 'innerDimensions', 'master' => 'masterDimensions'] as $type => $relation) {
                $model->$relation()->updateOrCreate(
                    ['type' => $type],
                    [
                        'width' => round(Arr::get($relationsValidation->validated, "{$type}_width") * $cmModifier, 3),
                        'depth' => round(Arr::get($relationsValidation->validated, "{$type}_depth") * $cmModifier, 3),
                        'height' => round(Arr::get($relationsValidation->validated, "{$type}_height") * $cmModifier, 3),
                        'gross_weight' => round(Arr::get($relationsValidation->validated, "{$type}_gross_weight") * $kgModifier, 3),
                        'net_weight' => round(Arr::get($relationsValidation->validated, "{$type}_net_weight") * $kgModifier, 3),
                    ]
                );
            }

            // Simple Relations
            $model->packagingMaterials()->sync(Arr::get($relationsValidation->validated, 'packaging_materials', []));
            $model->flags()->sync(Arr::get($relationsValidation->validated, 'flags', []));
            $model->certifications()->sync(Arr::get($relationsValidation->validated, 'certifications', []));
            $model->regulatoryInfo()->updateOrCreate([], $regulatoryValidation->validated);

            // Allergens
            $allergens = [];
            foreach (Arr::get($relationsValidation->validated, 'allergens', []) as $id => $value) {
                $allergens[$id] = ['contains' => $value];
            }
            $model->allergens()->sync($allergens);

            // Save custom labels for additional images
            if (is_array($request->file_label)) {
                foreach ($request->file_label as $id => $label) {
                    $media = Media::find($id);
                    if ($media) {
                        $media->setCustomProperty('label', $label);
                        $media->save();
                    }
                }
            }

            $model->handleMediaDeletion($isDuplicate);

            // Reload model
            $model = self::allStates()->withEagerLoadedRelations('history')->find($model->id);

            $errors = $model->stepErrors();
            if ($saveMessages->isNotEmpty()) {
                $errors->put('flash', $saveMessages);
                $saved = false;
            } else {
                $saved = true;
            }

            // DEV: Comment out for easier of testing.
            if ($submitting && $errors->allBagsEmpty()) {
                $model = $model->submitSignoff();
            }
        }

        $returnObj = new stdClass;
        $returnObj->model = $model;
        $returnObj->errors = $errors;
        $returnObj->saved = $saved;

        return $returnObj;
    }

    public function supplyExtra(string $event, array $properties, ?\Altek\Accountant\Contracts\Identifiable $user): array
    {
        $extra = [];
        if ($event == 'created' || $event == 'updated') {
            if (Features::accessible('remove-session-dependency')) {
                extract(Product::loadLookups($this));
            } else {
                extract(Session::get(static::getSessionRelationsKey()));
            }

            $supersedes = '';
            if ($properties['supersedes_id']) {
                $product = Product::select('id', 'name', 'name_fr', 'packaging_language')->find($properties['supersedes_id']);
                if ($product) {
                    $supersedes = $product->getName();
                }
            }

            $extra = [
                'brand_id' => $properties['brand_id'] > 0 ? $brands->find($properties['brand_id'])->name : '',
                'is_display' => $properties['is_display'] ? 'Yes' : 'No',
                'supersedes_id' => $supersedes,
                'country_origin' => $properties['country_origin'] > 0 ? $countries->find($properties['country_origin'])->name : '',
                'country_shipped' => $properties['country_shipped'] > 0 ? $countries->find($properties['country_origin'])->name : '',
                'packaging_language' => $properties['packaging_language'] ? Arr::get(self::PACKAGING_LANGUAGES, $properties['packaging_language']) : '',
                'category_id' => $properties['category_id'] > 0 ? $categories->find($properties['category_id'])->name : '',
                'subcategory_id' => $properties['subcategory_id'] > 0 ? ProductSubcategory::find($properties['subcategory_id'])->name : '',
                'catalogue_category_id' => $properties['catalogue_category_id'] > 0 ? CatalogueCategory::find($properties['catalogue_category_id'])->name : '',
                'not_for_resale' => $properties['not_for_resale'] ? 'Yes' : 'No',
                'purity_sell_by_unit' => $properties['purity_sell_by_unit'] > 0 ? Arr::get(self::SELL_BY_UNITS, $properties['purity_sell_by_unit']) : '',
                'uom_id' => $properties['uom_id'] > 0 ? $uoms->find($properties['uom_id'])->description : '',
                'tester_available' => $properties['tester_available'] ? 'Yes' : 'No',
                'shelf_life_units' => ucfirst($properties['shelf_life_units']),
            ];

            $retailerUnits = [];
            foreach (self::SELL_BY_UNITS as $key => $label) {
                if ($properties['retailer_sell_by_unit'] & $key) {
                    $retailerUnits[] = $label;
                }
            }
            $extra['retailer_sell_by_unit'] = implode(', ', $retailerUnits);

            // handle BelongsToMany relations
            if ($event == 'created') {
                $extra = array_merge($extra, $this->getRelationsExtra());
            }
        }

        return $extra;
    }

    public function stepErrors()
    {
        $errors = $this->baseStepErrors();
        $imagesErrorBag = $errors->getBag('images');

        // Always Mandatory
        if (! $this->getMedia('product')->count()) {
            $imagesErrorBag->add('product', 'Required');
        }
        if (! $this->getMedia('label_flat')->count()) {
            $imagesErrorBag->add('label_flat', 'Required');
        }

        // Category dependant
        if (optional($this->category)->requiresNutritionalFacts && ! $this->getMedia('nutritional_facts')->count()) {
            $imagesErrorBag->add('nutritional_facts', 'Required');
        }
        if (optional($this->category)->requiresIngredientPanel && ! $this->getMedia('ingredient_panel')->count()) {
            $imagesErrorBag->add('ingredient_panel', 'Required');
        }

        $errors->put('images', $imagesErrorBag);

        return $errors;
    }

    public function scopeWithAccess($query, $user = null)
    {
        if (! $user) {
            $user = auth()->user();
        }

        abort_if(! $user, 401, 'You must be logged in to access this resource.');

        // All Access
        if ($user->can('vendor.access-all')) {
            return $query;
        }

        if ($user->can('view', Product::class)) {
            if ($user->can('vendor')) {
                // Broker Access
                if ($user->can('user.assign.broker')) {
                    return $query->whereHas('brand.brokers', function ($query) use ($user) {
                        $query->where('id', $user->broker_id ?? -1);
                    });
                }

                // Vendor Access
                if ($user->can('user.assign.vendor')) {
                    return $query->whereHas('brand.vendor', function ($query) use ($user) {
                        $query->where('id', $user->vendor_id ?? -1);
                    });
                }
            }
        }

        $query->whereRaw('1 = 0');
    }

    public function scopeSignoffFilter($query, $user = null)
    {
        $query->whereHas('brand', function ($query) use ($user) {
            $query->signoffFilter($user);
        })->orWhere('signoffs.step', '<>', '1');
    }

    public function scopeCatalogueActive($query, $includeNonCatalogue = false)
    {
        $query->where(function ($query) use ($includeNonCatalogue) {
            $query->whereHas('as400StockData', function ($query) use ($includeNonCatalogue) {
                $query->where(function ($query) {
                    $query->where('status', 'A')
                        ->orWhere(function ($query) {
                            $query->whereIn('status', ['S', 'D'])->where('out_of_stock', false);
                        });
                })->when(! $includeNonCatalogue, fn ($query) => $query->where('hide_from_catalogue', false));
            });
        })->orWhere(function ($query) use ($includeNonCatalogue) {
            $query->whereHas('as400StockData', function ($query) use ($includeNonCatalogue) {
                $query->whereIn('status', ['S', 'D'])
                    ->when(! $includeNonCatalogue, fn ($query) => $query->where('hide_from_catalogue', false));
            })->whereHas('as400WarehouseStock', function ($query) {
                $query->where('quantity', '>', 0);
            });
        });
    }

    public function scopeDiscontinued($query)
    {
        $query->whereHas('as400StockData', function ($query) {
            $query->where([
                'hide_from_catalogue' => false,
                'status' => 'D',
            ]);
        });
    }

    public function scopeInStockDisco($query)
    {
        $query->whereHas('as400StockData', function ($query) {
            $query->where([
                'hide_from_catalogue' => false,
                'status' => 'D',
                'out_of_stock' => false,
            ]);
        })->where('stock_id', 'not like', '.%');
    }

    public function scopeForExport($query)
    {
        $query->where('hide_export', false)->whereHas('brand', function ($query) {
            $query->forExport();
        });
    }

    public function scopeWithPromoPricing($query, $periods, $allProducts = false, $withDisco = false, $includeNonCatalogue = false, $promo = null)
    {
        if (! ($periods instanceof Collection) || ! $periods->count()) {
            $periods = array_filter(Arr::wrap($periods));
            if (count($periods) && ! is_object($periods[0])) {
                $periods = PromoPeriod::findMany($periods);
            }
        }

        $startDate = null;
        $periodIds = [];
        foreach ($periods as $period) {
            $periodIds[] = $period->id;

            if (! $startDate || $period->start_date < $startDate) {
                $startDate = $period->start_date;
            }
        }

        $query->catalogueActive($includeNonCatalogue)
        // ->select('id', 'brand_id', 'name', 'stock_id', 'purity_sell_by_unit', 'upc', 'master_upc', 'master_units', 'inner_upc')
            ->with([
                'as400StockData' => function ($query) {
                    $query->select('product_id', 'out_of_stock', 'status');
                },
                'as400Pricing' => function ($query) {
                    $query->select('product_id', 'wholesale_price', 'po_price', 'average_landed_cost', 'taxable', 'next_po_price', 'po_price_expiry');
                },
                'as400UpcomingPriceChanges' => function ($query) use ($startDate) {
                    if ($startDate) {
                        $query->whereDate('change_date', '<=', $startDate);
                    } else {
                        $query->whereRaw('1 = 2');
                    }
                },
                'promoLineItems' => function ($query) use ($periodIds, $promo) {
                    $query->whereHas('promo', function ($query) use ($periodIds, $promo) {
                        $query->whereIn('period_id', $periodIds);
                        if ($promo) {
                            $query->where(function ($query) use ($promo) {
                                $query->where('id', $promo->id)
                                    ->orWhere(function ($query) use ($promo) {
                                        $query->where('period_id', '<>', $promo->period_id)
                                            ->where('state', SignoffStateHelper::INITIAL);
                                    });
                            });
                        } else {
                            $query->where('state', SignoffStateHelper::INITIAL);
                        }
                    })->with(['promo' => function ($query) use ($periodIds, $promo) {
                        $query->whereIn('period_id', $periodIds);
                        if ($promo) {
                            $query->where(function ($query) use ($promo) {
                                $query->where('id', $promo->id)
                                    ->orWhere(function ($query) use ($promo) {
                                        $query->where('period_id', '<>', $promo->period_id)
                                            ->where('state', SignoffStateHelper::INITIAL);
                                    });
                            });
                        } else {
                            $query->where('state', SignoffStateHelper::INITIAL);
                        }
                    }]);
                },
                'brand' => function ($query) {
                    $query->with(['currency' => function ($query) {
                        $query->select('id', 'exchange_rate');
                    }])->with(['as400Margin' => function ($query) {
                        $query->select('brand_id', 'margin');
                    }])->select('id', 'name', 'currency_id')
                        ->withCount(['products' => function ($query) {
                            $query->catalogueActive()
                                ->where('not_for_resale', false)
                                ->whereHas('as400Pricing', function ($query) {
                                    $query->where('wholesale_price', '>', 0);
                                });
                        }]);
                },
            ]);

        if ($withDisco) {
            $query->with(['discoPromo' => function ($query) {
                $query->select('product_id', 'pl_discount', 'brand_discount');
            }]);
        }

        if (! $allProducts) {
            $query->where(function ($query) use ($periodIds, $withDisco) {
                $query->whereHas('promoLineItems.promo', function ($query) use ($periodIds) {
                    $query->whereIn('period_id', $periodIds);
                });
                if ($withDisco) {
                    $query->orWhereHas('discoPromo');
                }
            });
        }
    }

    public function getDisplayNameAttribute()
    {
        $brand = $this->brand ? $this->brand->name : 'Missing Brand';
        $stockId = $this->stock_id ? "#{$this->stock_id}, " : null;

        return "{$this->getName()} [{$stockId}{$brand}]";
    }

    public function getStepsAttribute()
    {
        $steps = [
            'general' => [
                'display' => 'General',
                'formRequest' => \App\Http\Requests\Products\GeneralStepFormRequest::class,
            ],
            'packaging' => [
                'display' => 'Packaging',
                'formRequest' => \App\Http\Requests\Products\PackagingStepFormRequest::class,
            ],
            'pricing' => [
                'display' => 'Pricing',
                'formRequest' => \App\Http\Requests\Products\PricingStepFormRequest::class,
            ],
            'regulatory' => [
                'display' => 'Regulatory',
                'formRequest' => \App\Http\Requests\Products\RegulatoryStepFormRequest::class,
            ],
            'details' => [
                'display' => 'Details',
                'formRequest' => \App\Http\Requests\Products\DetailsStepFormRequest::class,
            ],
            'images' => [
                'display' => 'Images',
                'formRequest' => \App\Http\Requests\Products\ImagesStepFormRequest::class,
            ],
            'review' => [
                'display' => 'Review',
                'formRequest' => \App\Http\Requests\Products\ReviewStepFormRequest::class,
            ],
        ];

        if (! $this->category_id) {
            $steps['regulatory']['hidden'] = true;
        } else {
            if (! isset($this->relations['category'])) {
                $this->load('category');
            }
            if (! $this->hasRegulations) {
                $steps['regulatory']['hidden'] = true;
            }
        }

        return $steps;
    }

    public function getFilteredAttributes()
    {
        return array_filter([
            'name' => $this->name,
            'name_fr' => $this->name_fr,
            'catalogue_category_id' => $this->catalogue_category_id,
        ]);
    }

    public function getName()
    {
        return $this->packaging_language == 'F' ? $this->name_fr : $this->name;
    }

    public function getNameFr()
    {
        return $this->name_fr ?: $this->name;
    }

    public function getAllergenStatus($allergin)
    {
        if (! $this->allergens->contains($allergin)) {
            return 0;
        }

        return $this->allergens->find($allergin->id)->pivot->contains;
    }

    public function getFeaturesAttribute($value)
    {
        return [
            $this->features_1,
            $this->features_2,
            $this->features_3,
            $this->features_4,
            $this->features_5,
        ];
    }

    public function getFeaturesFRAttribute($value)
    {
        return [
            $this->features_fr_1,
            $this->features_fr_2,
            $this->features_fr_3,
            $this->features_fr_4,
            $this->features_fr_5,
        ];
    }

    public function getMinimumSellByAttribute()
    {
        if ($this->purity_sell_by_unit == 2) {
            return $this->inner_units > 1 ? $this->inner_units : ($this->master_units > 1 ? $this->master_units : 1);
        } elseif ($this->purity_sell_by_unit == 4) {
            return $this->master_units > 1 ? $this->master_units : ($this->inner_units > 1 ? $this->inner_units : 1);
        }

        return 1;
    }

    public function getCaseSizeAttribute()
    {
        if ($this->purity_sell_by_unit == 4 && $this->master_units > 1) {
            return $this->master_units;
        }

        return $this->inner_units > 1 ? $this->inner_units : 1;
    }

    public function getCatalogueCaseSizeAttribute()
    {
        if ($this->purity_sell_by_unit == 1 && $this->inner_units > 1) {
            return $this->inner_units;
        }

        return $this->master_units > 1 ? $this->master_units : ($this->inner_units ?: 1);
    }

    public function getSoldByCaseAttribute()
    {
        return $this->purity_sell_by_unit !== 1;
    }

    public function getCaseUPCAttribute()
    {
        if ($this->purity_sell_by_unit == 4 && $this->master_units > 0 && $this->master_upc) {
            return $this->master_upc;
        }

        return $this->inner_upc;
    }

    public function getSellByUPCAttribute()
    {
        return $this->soldByCase ? $this->caseUPC : $this->upc;
    }

    public function getFormattedSellByUPCAttribute()
    {
        $upc = $this->sellByUPC;

        if (strlen($upc) == 12) {
            return substr($upc, 0, 1) . ' ' . substr($upc, 1, 5) . ' ' . substr($upc, 6, 5) . ' ' . substr($upc, -1);
        } elseif (strlen($upc) == 14) {
            return substr($upc, 0, 1) . ' ' . substr($upc, 1, 2) . ' ' . substr($upc, 3, 5) . ' ' . substr($upc, 9, 5) . ' ' . substr($upc, -1);
        }

        return $upc;
    }

    public function getHasRegulationsAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasRegulations : optional($this->category)->hasRegulations;
    }

    public function getHasWarningsAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasWarnings : optional($this->category)->hasWarnings;
    }

    public function getHasRecommendedDosageAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasRecommendedDosage : optional($this->category)->hasRecommendedDosage;
    }

    public function getHasRecommendedUseAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasRecommendedUse : optional($this->category)->hasRecommendedUse;
    }

    public function getRequiresNutritionalFactsAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->requiresNutritionalFacts : optional($this->category)->requiresNutritionalFacts;
    }

    public function getRequiresIngredientPanelAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->requiresIngredientPanel : optional($this->category)->requiresIngredientPanel;
    }

    public function getHasNPNAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasNPN : optional($this->category)->hasNPN;
    }

    public function getRequiresNPNAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->requiresNPN : optional($this->category)->requiresNPN;
    }

    public function getRequiresCosmeticLicenseAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->requiresCosmeticLicense : optional($this->category)->requiresCosmeticLicense;
    }

    public function getRequiresImporterAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->requiresImporter : optional($this->category)->requiresImporter;
    }

    public function getRequiresCNNAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->requiresCNN : optional($this->category)->requiresCNN;
    }

    public function getIsMedicalDeviceAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->isMedicalDevice : optional($this->category)->isMedicalDevice;
    }

    public function getHasNutritionalInfoAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasNutritionalInfo : optional($this->category)->hasNutritionalInfo;
    }

    public function getIsPesticideAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->isPesticide : optional($this->category)->isPesticide;
    }

    public function getHasNetWeightAttribute()
    {
        return optional($this->subcategory)->flags ? $this->subcategory->hasNetWeight : optional($this->category)->hasNetWeight;
    }

    public function getReceiveAttributeAttribute()
    {
        return optional($this->regulatoryInfo)->npn ? '1111110100' : optional($this->category)->receive_attribute;
    }

    public function getSize($includeSpace = false)
    {
        $size = round($this->size, 2) ?? 1;
        $uom = $this->uom_id ? $this->uom->unit : 'un';
        $space = $includeSpace ? ' ' : '';

        return "{$size}{$space}{$uom}";
    }

    public function getSizeWithUnits()
    {
        if (! $this->soldByCase) {
            return $this->getSize();
        }

        return "{$this->caseSize} x {$this->getSize()}";
    }

    public function getSizeFR($includeSpace = false)
    {
        $size = round($this->size, 2) ?? 1;
        $uom = $this->uom_id ? $this->uom->unit_fr : 'un';
        $space = $includeSpace ? ' ' : '';

        return "{$size}{$space}{$uom}";
    }

    public function getSizeWithUnitsFR()
    {
        if (! $this->soldByCase) {
            return $this->getSizeFR();
        }

        return "{$this->caseSize} x {$this->getSizeFR()}";
    }

    public function getLongSize()
    {
        $size = round($this->size, 2) ?? 1;
        $uom = $this->uom_id ? $this->uom->description : 'Unit';
        if ($size == 1) {
            $uom = Str::singular($uom);
        }

        return "{$size} {$uom}";
    }

    public function getLongSizeWithUnits()
    {
        if (! $this->soldByCase) {
            return $this->getLongSize();
        }

        return "{$this->caseSize} x {$this->getLongSize()}";
    }

    public function getLongSizeFR()
    {
        $size = round($this->size, 2) ?? 1;
        $uom = $this->uom_id ? $this->uom->description_fr : 'Unit';
        if ($size == 1) {
            $uom = Str::singular($uom);
        }

        return "{$size} {$uom}";
    }

    public function getLongSizeWithUnitsFR()
    {
        if (! $this->soldByCase) {
            return $this->getLongSizeFR();
        }

        return "{$this->caseSize} x {$this->getLongSizeFR()}";
    }

    public function getShelfLife()
    {
        $sl = $this->shelf_life ?? 1;
        $units = ucfirst($this->shelf_life_units ?? 'months');
        if ($sl == 1) {
            $units = Str::singular($units);
        }

        return "{$sl} {$units}";
    }

    public function convertToUnitPrice($price)
    {
        return round($price / $this->caseSize, 2);
    }

    public function getUnitPrice($date = null, $priceCode = null)
    {
        $price = $this->getPrice($date, $priceCode);

        return round($price / $this->caseSize, 2);
    }

    public function getLandedCost($date = null)
    {
        $date = $date ? new Carbon($date) : null;

        if ($date && $this->futureLandedCosts) {
            $futureLandedCost = $this->futureLandedCosts
                ->where('change_date', '<=', $date)
                ->sortByDesc('change_date')
                ->first();

            if ($futureLandedCost) {
                return $futureLandedCost->landed_cost;
            }
        }

        return $this->landed_cost;
    }

    public function getPrice($date = null, $priceCode = null)
    {
        $date = $date ? new Carbon($date) : null;

        // If not using special price code, just return wholesale_price
        if (! $priceCode) {
            $newPrice = null;
            if ($date && $this->as400UpcomingPriceChanges) {
                $priceChange = $this->as400UpcomingPriceChanges->sortByDesc('change_date')->where('change_date', '<=', $date)->first();
                if ($priceChange) {
                    $newPrice = $priceChange->wholesale_price;
                }
            }

            $price = ($newPrice ? $newPrice : optional($this->as400Pricing)->wholesale_price) ?? 0;
        } else {
            // Product Pricing
            $ogd = $this->getOGD($priceCode, $date);
            $ogdPrice = $ogd['price'];
            $ogdDiscount = $ogd['discount'];

            $price = ($ogdPrice > 0 ? $ogdPrice : optional($this->as400Pricing)->wholesale_price) ?? 0;
            if ($ogdDiscount > 0) {
                $price = round($price * (1 - $ogdDiscount), 2);
            }
        }

        return (float) $price;
    }

    public function getOGD($priceCode, $date = null)
    {
        $date = $date ? new Carbon($date) : null;
        // Load special pricing relation if not already loaded
        if (! isset($this->relations['as400SpecialPricing'])) {
            $this->load(['as400SpecialPricing' => function ($query) use ($priceCode, $date) {
                $query->byCode($priceCode)->forDate($date);
            }]);
        }
        if (! isset($this->relations['brand'])) {
            $this->load(['brand' => function ($query) use ($priceCode, $date) {
                $query->with(['as400SpecialPricing' => function ($query) use ($priceCode, $date) {
                    $query->byCode($priceCode)->forDate($date);
                }])->select('id');
            }]);
        }

        // if no date set, use today
        $date = $date ? new Carbon($date) : Carbon::now();

        $ogdPrice = null;
        $ogdDiscount = null;

        $specialPricing = $this->as400SpecialPricing->where('price_code', $priceCode)->filter(function ($price) use ($date) {
            return $date->greaterThanOrEqualTo($price->start_date) && $date->lessThanOrEqualTo($price->end_date);
        })->first();

        $brandSpecialPricing = $this->brand->as400SpecialPricing->where('price_code', $priceCode)->filter(function ($price) use ($date) {
            return $date->greaterThanOrEqualTo($price->start_date) && $date->lessThanOrEqualTo($price->end_date);
        })->first();

        if ($specialPricing && $brandSpecialPricing) {
            $ogdPrice = min($specialPricing->price, $brandSpecialPricing->price);
            $ogdDiscount = max($specialPricing->percent_discount, $brandSpecialPricing->percent_discount);
        } elseif ($specialPricing || $brandSpecialPricing) {
            $ogdPrice = $specialPricing ? $specialPricing->price : $brandSpecialPricing->price;
            $ogdDiscount = $specialPricing ? $specialPricing->percent_discount : $brandSpecialPricing->percent_discount;
        }

        return [
            'price' => $ogdPrice,
            'discount' => $ogdDiscount,
        ];
    }

    public function getPromoLineItem($period, $returnNull = false)
    {
        $periodId = is_object($period) ? $period->id : $period;

        return $this->promoLineItems->where('promo.period_id', $periodId)->first() ?? ($returnNull ? null : new PromoLineItem);
    }

    public function calculatePromoPrice($period, $date = null, $brandOnly = false, $includeDisco = false, &$outPercent = null)
    {
        $regTotalDiscount = null;
        $regOutPercent = null;

        $lineItem = null;
        if ($includeDisco && isset($this->relations['discoPromo']) && $this->discoPromo) {
            $regTotalDiscount = $this->calculatePromoPrice($period, $date, $brandOnly, false, $regOutPercent);
            $lineItem = $this->discoPromo;
        } else {
            $lineItem = $this->getPromoLineItem($period, true);
        }

        $totalDiscount = null;
        $outPercent = null;
        if ($lineItem) {
            $exchangeRate = 1;
            if ($this->brand && $this->brand->currency) {
                $exchangeRate = $this->brand->currency->exchange_rate;
            }

            $price = $this->getPrice($date);
            if (! $price) {
                return;
            }

            $totalDiscount = $brandOnly ? 0 : $lineItem->pl_discount;
            if (optional($lineItem->promo)->dollar_discount) {
                $totalDiscount += (($lineItem->brand_discount * $exchangeRate) / $price) * 100;
            } else {
                if ($lineItem->oi) {
                    $poExpiry = new Carbon($this->as400Pricing->po_price_expiry);
                    $poPrice = $this->as400Pricing->next_po_price && $poExpiry->gte(Carbon::now()) ? $this->as400Pricing->next_po_price : $this->as400Pricing->po_price;

                    $totalDiscount += (round($poPrice * (($lineItem->brand_discount * $exchangeRate) / 100), 2) / $price) * 100;
                } else {
                    $totalDiscount += $lineItem->brand_discount;
                }
            }

            $outPercent = round($totalDiscount, 2);
            $totalDiscount = round($price * (1 - ($totalDiscount / 100)), 2);
        }

        if ($outPercent && $regOutPercent) {
            if ($outPercent > $regOutPercent) {
                return $totalDiscount;
            } else {
                $outPercent = $regOutPercent;

                return $regTotalDiscount;
            }
        } elseif ($regOutPercent) {
            $outPercent = $regOutPercent;

            return $regTotalDiscount;
        }

        return $totalDiscount;
    }

    public function calculatePromoDiscount($period, $date = null, $brandOnly = false, $includeDisco = false, &$outPrice = null)
    {
        $discount = null;
        $outPrice = $this->calculatePromoPrice($period, $date, $brandOnly, $includeDisco, $discount);

        return $discount ?? 0;
    }

    public function calculateCombinedPromoPrice($period, $basePeriod, $date = null, $brandOnly = false, $includeDisco = false, &$outPercent = null)
    {
        $outPrice = null;
        $outPercent = $this->calculateCombinedPromoDiscount($period, $basePeriod, $date, $brandOnly, $includeDisco, $outPrice);

        return $outPrice;
    }

    public function calculateCombinedPromoDiscount($period, $basePeriod, $date = null, $brandOnly = false, $includeDisco = false, &$outPrice = null)
    {
        // TODO: Temporary fix to prevent layering disco promos and inflating the discount
        if ($period && $basePeriod) {
            $includeDisco = false;
        }

        $discount = $this->calculatePromoDiscount($period, $date, $brandOnly, $includeDisco);

        // Never use disco on second discount to prevent doubling the disco amount.
        $baseDiscount = $this->calculatePromoDiscount($basePeriod, $date, $brandOnly, false);

        if (! $discount && ! $baseDiscount) {
            return 0;
        }

        $totalDiscount = ($discount ?? 0) + ($baseDiscount ?? 0);
        $basePrice = $this->getPrice($date);
        $outPrice = round($basePrice * (1 - ($totalDiscount / 100)), 2);

        return $totalDiscount;
    }

    public function getRequiresQCSignoffAttribute()
    {
        // If an Update or Canadian product, skip QC
        if (! $this->isNewSubmission || $this->country_shipped == 40) {
            return false;
        }

        if ($this->category_id == 3) {
            return true;
        }

        if (! empty(optional($this->regulatoryInfo)->npn)) {
            return true;
        }

        return false;
        // return $this->category_id == 3 || !empty(optional($this->regulatoryInfo)->npn);
    }

    public function nextStep($step, $signoff)
    {
        $step++;

        if ($step == 2) {
            //  QC
            if (! $signoff->proposed->requiresQCSignoff) {
                $step++;
            }
        }

        if ($step == 4) {
            //  Management
            $hasWholesale = $this->wholesale_price && $this->wholesale_price != $this->old_wholesale_price;
            if (! $this->unit_cost && ! $hasWholesale) {
                $step++;
            }
        }

        return $step;
    }

    public function prevStep($step, $signoff)
    {
        $step--;

        if ($step == 4) {
            return 1;
        }

        if ($step == 2) {
            // QC
            if (! $signoff->proposed->requiresQCSignoff) {
                $step--;
            }
        }

        return $step;
    }

    public function onSignoffComplete($signoff)
    {
        if ($signoff->new_submission) {
            $this->listed_on = Carbon::now();

            $stockData = new \App\Models\AS400\AS400StockData;
            $stockData->product_id = $this->id;
            $stockData->status = 'A';
            $stockData->description = '';
            $stockData->category_code = '';
            $stockData->save();
        }

        // Clear out unit cost / price change / notes fields
        $this->unit_cost = null;
        $this->price_change_reason = null;
        $this->price_change_date = null;
        $this->submission_notes = null;

        // Check if we need to create a new catalogue category
        $proposed = $signoff->proposed;
        if (! $proposed->catalogue_category_id && $proposed->catalogue_category_proposal) {
            $category = CatalogueCategory::where(['brand_id' => $this->brand_id])
                ->where(function ($query) use ($proposed) {
                    $query->where('name', trim($proposed->catalogue_category_proposal));
                    if (trim($proposed->catalogue_category_proposal_fr)) {
                        $query->orWhere('name_fr', trim($proposed->catalogue_category_proposal_fr));
                    }
                })->first();

            if ($category) {
                $dirty = false;
                if (trim($proposed->catalogue_category_proposal) && $category->name !== trim($proposed->catalogue_category_proposal)) {
                    $category->name = trim($proposed->catalogue_category_proposal);
                    $dirty = true;
                }
                if (trim($proposed->catalogue_category_proposal_fr) && $category->name_fr !== trim($proposed->catalogue_category_proposal_fr)) {
                    $category->name_fr = trim($proposed->catalogue_category_proposal_fr);
                    $dirty = true;
                }

                $dirty && $category->save();
            } else {
                $maxSort = CatalogueCategory::where('brand_id', $this->brand_id)->max('sort');

                $category = new CatalogueCategory;
                $category->brand_id = $this->brand_id;
                $category->name = trim($proposed->catalogue_category_proposal);
                $category->name_fr = trim($proposed->catalogue_category_proposal_fr);

                $category->sort = $maxSort + 1;
                $category->save();
            }

            $this->catalogue_category_id = $category->id;
        }

        // Save future landed cost
        if (! $signoff->new_submission && $proposed->price_change_date && $proposed->landed_cost && $proposed->landed_cost !== $this->landed_cost) {
            $futureLandedCost = $signoff->initial->futureLandedCosts()->where('change_date', $proposed->price_change_date)->firstOrNew();

            $futureLandedCost->product_id = $this->id;
            $futureLandedCost->landed_cost = $proposed->landed_cost;
            $futureLandedCost->change_date = $proposed->price_change_date;
            $futureLandedCost->save();
        } elseif ((! $proposed->price_change_date || $signoff->new_submission) && $proposed->landed_cost) {
            // Because landed cost is ignored on clone
            $this->landed_cost = $proposed->landed_cost;
        }

        // Always save initial to ensure price change values are cleared
        $this->save();
    }

    public function getSummaryArray($signoff)
    {
        $summary = [
            'Product Name' => $this->name,
            'Brand' => $this->brand->name,
        ];

        if ($this->stock_id) {
            $summary['Purity Stock Id'] = $this->stock_id;
        }

        return $summary;
    }

    public function catalogueFormat($period1, $period2, $period3, $startDate, $cutoffStart, $cutoffEnd, $english)
    {
        $catData = '<CharStyle:><ParaStyle:FOOD ProductList><CharStyle:' . (optional($this->as400StockData)->status == 'D' ? 'Disco' : 'No Style') . '>';
        $catData .= $this->stock_id . "\t";
        $catData .= ($this->listed_on >= $cutoffStart && $this->listed_on <= $cutoffEnd ? $this->getName() : $this->getName()) . "\t";
        $catData .= ($english ? $this->getSizeWithUnits() : $this->getSizeWithUnitsFR()) . "\t";
        $catData .= "<CharStyle:Blue>{{PROMO_DISCOUNT1}}\t{{PROMO_DISCOUNT2}}\t{{PROMO_DISCOUNT3}}";
        $catData .= "<CharStyle:><CharStyle:No Style>\t" . $this->catalogueCaseSize . "\t";
        $catData .= $this->formattedSellByUPC . "\t";

        $catData .= $this->allergens->count() ? "\t" : "A\t";

        $certifications = $this->certifications->pluck('name')->toArray();
        $catData .= in_array('Gluten Free', $certifications) ? "G\t" : "\t";
        $catData .= in_array('Organic', $certifications) ? "O\t" : "\t";
        $catData .= in_array('GMO Free', $certifications) ? "N\t" : "\t";
        $catData .= in_array('Kosher', $certifications) ? "K\t" : "\t";
        $catData .= in_array('Vegan', $certifications) ? "V\t" : "\t";
        $catData .= in_array('Halal', $certifications) ? "H\t" : "\t";
        $catData .= in_array('Fair Trade', $certifications) ? "F\t" : "\t";

        $catData .= $this->packaging_language;
        if (optional($this->subcategory)->grocery) {
            $catData .= "\t<cTypeface:Color><cLigatures:0><cCase:Normal><cFont:EmojiOne><cOTFContAlt:0><0xD83D><0xDED2><cLigatures:><cCase:><cFont:><cOTFContAlt:><CharStyle:><CharStyle:No Style>";
        }

        $promoDiscount1 = round($this->calculateCombinedPromoDiscount($period1, optional($period1)->basePeriod, $startDate, false, true));
        $promoDiscount2 = round($this->calculateCombinedPromoDiscount($period2, optional($period2)->basePeriod, $startDate, false, true));
        $promoDiscount3 = round($this->calculateCombinedPromoDiscount($period3, optional($period3)->basePeriod, $startDate, false, true));
        $catData = str_replace('{{PROMO_DISCOUNT1}}', $promoDiscount1 ? $promoDiscount1 . '%' : '', $catData);
        $catData = str_replace('{{PROMO_DISCOUNT2}}', $promoDiscount2 ? $promoDiscount2 . '%' : '', $catData);
        $catData = str_replace('{{PROMO_DISCOUNT3}}', $promoDiscount3 ? $promoDiscount3 . '%' : '', $catData);

        return $catData;
    }

    public function catalogueFormatOld($period1, $period2, $startDate, $cutoffStart, $cutoffEnd, $english)
    {
        $price = $this->getPrice($startDate);

        $catData = '<CharStyle:><ParaStyle:FOOD ProductList><CharStyle:' . (optional($this->as400StockData)->status == 'D' ? 'Disco' : 'No Style') . '>';
        $catData .= $this->stock_id . "\t";
        $catData .= ($this->listed_on >= $cutoffStart && $this->listed_on <= $cutoffEnd ? "<CharStyle:Red>{$this->getName()} - New<CharStyle:No Style>" : $this->getName()) . "\t";
        $catData .= ($english ? $this->getSizeWithUnits() : $this->getSizeWithUnitsFR()) . "\t";
        $catData .= ($price > 0 ? number_format($this->getPrice($startDate), 2) : '') . "\t";
        $catData .= "<CharStyle:Blue>{{PROMO_DISCOUNT}}<CharStyle:><CharStyle:Mo>\t{{PROMO_MONTH}}\t<CharStyle:Blue>{{PROMO_PRICE}}";
        $catData .= "<CharStyle:><CharStyle:No Style>\t" . $this->catalogueCaseSize . "\t";
        $catData .= $this->formattedSellByUPC . "\t";

        $catData .= $this->allergens->count() ? "\t" : "A\t";

        $certifications = $this->certifications->pluck('name')->toArray();
        $catData .= in_array('Gluten Free', $certifications) ? "G\t" : "\t";
        $catData .= in_array('Organic', $certifications) ? "O\t" : "\t";
        $catData .= in_array('GMO Free', $certifications) ? "N\t" : "\t";
        $catData .= in_array('Kosher', $certifications) ? "K\t" : "\t";
        $catData .= in_array('Vegan', $certifications) ? "V\t" : "\t";
        $catData .= in_array('Halal', $certifications) ? "H\t" : "\t";
        $catData .= in_array('Fair Trade', $certifications) ? "F\t" : "\t";

        $catData .= $this->packaging_language;
        if (optional($this->subcategory)->grocery) {
            $catData .= "\t<cTypeface:Color><cLigatures:0><cCase:Normal><cFont:EmojiOne><cOTFContAlt:0><0xD83D><0xDED2><cLigatures:><cCase:><cFont:><cOTFContAlt:><CharStyle:><CharStyle:No Style>";
        }

        $promoPrice1 = null;
        $promoDiscount1 = round($this->calculateCombinedPromoDiscount($period1, optional($period1)->basePeriod, $startDate, false, true, $promoPrice1));
        $promoPrice2 = null;
        $promoDiscount2 = round($this->calculateCombinedPromoDiscount($period2, optional($period2)->basePeriod, $startDate, false, true, $promoPrice2));

        if ($promoDiscount1 > 0 || $promoDiscount2 > 0) {
            if ($promoDiscount1 > 0 && $promoDiscount2 > 0 && $promoDiscount1 != $promoDiscount2) {
                $cat1 = str_replace('{{PROMO_DISCOUNT}}', $promoDiscount1 . '%', $catData);
                $cat1 = str_replace('{{PROMO_MONTH}}', substr($period1->start_date->monthName, 0, 1), $cat1);
                $cat1 = str_replace('{{PROMO_PRICE}}', number_format($promoPrice1, 2), $cat1);

                $cat2 = str_replace('{{PROMO_DISCOUNT}}', $promoDiscount2 . '%', $catData);
                $cat2 = str_replace('{{PROMO_MONTH}}', substr($period2->start_date->monthName, 0, 1), $cat2);
                $cat2 = str_replace('{{PROMO_PRICE}}', number_format($promoPrice2, 2), $cat2);

                $catData = "{$cat1}\n{$cat2}";
            } elseif ($promoDiscount1 == $promoDiscount2) {
                $catData = str_replace('{{PROMO_DISCOUNT}}', $promoDiscount1 . '%', $catData);
                $catData = str_replace('{{PROMO_MONTH}}', 'B', $catData);
                $catData = str_replace('{{PROMO_PRICE}}', number_format($promoPrice1, 2), $catData);
            } elseif ($promoDiscount1 > 0) {
                $catData = str_replace('{{PROMO_DISCOUNT}}', $promoDiscount1 . '%', $catData);
                $catData = str_replace('{{PROMO_MONTH}}', substr($period1->start_date->monthName, 0, 1), $catData);
                $catData = str_replace('{{PROMO_PRICE}}', number_format($promoPrice1, 2), $catData);
            } else {
                $catData = str_replace('{{PROMO_DISCOUNT}}', $promoDiscount2 . '%', $catData);
                $catData = str_replace('{{PROMO_MONTH}}', substr($period2->start_date->monthName, 0, 1), $catData);
                $catData = str_replace('{{PROMO_PRICE}}', number_format($promoPrice2, 2), $catData);
            }
        } else {
            $catData = str_replace('{{PROMO_DISCOUNT}}', '', $catData);
            $catData = str_replace('{{PROMO_MONTH}}', '', $catData);
            $catData = str_replace('{{PROMO_PRICE}}', '', $catData);
        }

        return $catData;
    }

    public function countryOrigin(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_origin');
    }

    public function countryShipped(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_shipped');
    }

    public function supersedes(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'supersedes_id')->select('id', 'stock_id', 'name', 'name_fr', 'packaging_language', 'size')->with('uom');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(ProductSubcategory::class);
    }

    public function catalogueCategory(): BelongsTo
    {
        return $this->belongsTo(CatalogueCategory::class);
    }

    public function dimensions(): HasOne
    {
        return $this->hasOne(Dimensions::class)->where('type', 'unit');
    }

    public function innerDimensions(): HasOne
    {
        return $this->hasOne(Dimensions::class)->where('type', 'inner');
    }

    public function masterDimensions(): HasOne
    {
        return $this->hasOne(Dimensions::class)->where('type', 'master');
    }

    public function packagingMaterials(): BelongsToMany
    {
        return $this->belongsToMany(PackagingMaterial::class);
    }

    public function flags(): BelongsToMany
    {
        return $this->belongsToMany(ProductFlag::class);
    }

    public function allergens(): BelongsToMany
    {
        return $this->belongsToMany(Allergen::class)->withPivot('contains');
    }

    public function certifications(): BelongsToMany
    {
        return $this->belongsToMany(Certification::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class)->withPending();
    }

    public function regulatoryInfo(): HasOne
    {
        return $this->hasOne(RegulatoryInfo::class);
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }

    public function promos(): BelongsToMany
    {
        return $this->belongsToMany(Promo::class, 'promo_line_items', 'product_id', 'promo_id')
            ->allStates()
            ->withPivot('brand_discount', 'pl_discount', 'data')
            ->using(JsonDataPivot::class);
    }

    public function promoLineItems(): HasMany
    {
        return $this->hasMany(PromoLineItem::class);
    }

    public function discoPromo(): HasOne
    {
        return $this->hasOne(DiscoPromoLineItem::class);
    }

    public function futureLandedCosts(): HasMany
    {
        return $this->hasMany(FutureLandedCost::class);
    }

    public function retailerListings(): HasMany
    {
        return $this->hasMany(RetailerListing::class);
    }

    // AS400 Relations
    public function as400Pricing(): HasOne
    {
        return $this->hasOne(AS400Pricing::class);
    }

    public function as400PricingClone(): HasOne
    {
        return $this->hasOne(AS400Pricing::class, 'product_id', 'cloned_from_id');
    }

    public function as400SpecialPricing(): MorphMany
    {
        return $this->morphMany(AS400SpecialPricing::class, 'priceable');
    }

    public function as400StockData(): HasOne
    {
        return $this->hasOne(AS400StockData::class);
    }

    public function as400StockDataClone(): HasOne
    {
        return $this->hasOne(AS400StockData::class, 'product_id', 'cloned_from_id');
    }

    public function as400Supersedes(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'as400_supersedes', 'superseding_id', 'superseded_id');
    }

    public function as400SupersededBy(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'as400_supersedes', 'superseded_id', 'superseding_id');
    }

    public function as400UpcomingPriceChanges(): HasMany
    {
        return $this->hasMany(AS400UpcomingPriceChange::class);
    }

    public function as400WarehouseStock(): HasMany
    {
        return $this->hasMany(AS400WarehouseStock::class);
    }

    public function productDelistRequests(): HasMany
    {
        return $this->hasMany(ProductDelistRequest::class);
    }
}
