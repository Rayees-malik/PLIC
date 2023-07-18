<div id="general" class="js-stepper-step stepper-step active">
    {!! BladeHelper::uploaderModelFields($model) !!}
    @include('partials.stepper.flash-error')
    <input type="hidden" name="id" class="js-model-id" value="{{ $model->id }}">
    <input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
    <h3 class="form-section-title">
        General
        @if (isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D')
        <span class="float-right">RELIST</span>
        @endif
    </h3>
    <div class="row">
        @if (count($brands) > 1)
        <div class="dropdown-wrap col-xl-4 {{ $errors->general->has('brand_id') ? ' dropdown-danger' : '' }}">
            <label>Brand</label>
            <div class="dropdown-icon">
                <select name="brand_id" class="js-brand">
                    @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}" {{ old('brand_id', $model->brand_id) == $brand->id ? 'selected' : '' }}>
                        {{ $brand->name }}{{ $brand->brand_number ? " ({$brand->brand_number})" : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->general->has('brand_id'))
            <small class="info-danger">{{ $errors->general->first('brand_id') }}</small>
            @endif
        </div>
        @elseif ($brands)
        <div class="col-xl-4">
            <h4>{{ $brands->first()->name }}{{ $brands->first()->brand_number ? " ({$brands->first()->brand_number})" : '' }}</h4>
            <input type="hidden" name="brand_id" class="js-brand" value="{{ $brands->first()->id }}">
        </div>
        @endif
        @if ($model->signoff && $model->signoff->step == 3 && $model->isNewSubmission && auth()->user()->can('product.edit.stockid'))
        <div class="input-wrap col-xl-4 {{ $errors->general->has('stock_id') ? ' input-danger' : '' }}">
            <label>Stock Id
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="stock_id" class="js-cat-field" data-cat-target="stock_id" value="{{ old('stock_id', $model->stock_id) }}">
                </div>
            </label>
            @if ($errors->general->has('stock_id'))
            <small class="info-danger">{{ $errors->general->first('stock_id') }}</small>
            @endif
        </div>
        @elseif ($model->stock_id)
        <div class="col-xl-4">
            <label>Stock Id
                <h2 class="mb-0">{{ $model->stock_id }}</h2>
                <input type="hidden" class="js-cat-field" data-cat-target="stock_id" value="{{ $model->stock_id }}">
            </label>
        </div>
        @endif
        <div class="col-xl-4">
            <br />
            <div class="checkbox-wrap mt-2">
                <label class="checkbox">
                    <input type="hidden" class="no-history" name="is_display" value="0">
                    <input type="checkbox" name="is_display" value="1" {{ old('is_display', $model->is_display) ? 'checked' : '' }}>
                    <span class="checkbox-checkmark"></span>
                    <span class="checkbox-label">Is Display</span>
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->general->has('name') ? ' input-danger' : '' }}">
            <label>Product Name (EN)
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="name" class="js-cat-field" data-cat-target="name" value="{{ old('name', $model->name) }}">
                </div>
            </label>
            @if ($errors->general->has('name'))
            <small class="info-danger">{{ $errors->general->first('name') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->general->has('name_fr') ? ' input-danger' : '' }}">
            <label>Product Name (FR)
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="name_fr" value="{{ old('name_fr', $model->name_fr) }}">
                </div>
            </label>
            @if ($errors->general->has('name_fr'))
            <small class="info-danger">{{ $errors->general->first('name_fr') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="dropdown-wrap col-xl-8 {{ $errors->general->has('supersedes_id') ? ' dropdown-danger' : '' }}">
            <label>Supersedes</label>
            <div class="dropdown-icon">
                <select name="supersedes_id" class="js-supersedes ajax" data-placeholder="Start typing to search...">
                    <option></option>
                    @if ($model->supersedes_id)
                    <option value="{{ $model->supersedes->id }}" selected>{{ $model->supersedes->stock_id }} - {{ $model->supersedes->getName() }} ({{ $model->supersedes->getSize() }})</option>
                    @endif
                </select>
            </div>
            @if ($errors->general->has('supersedes_id'))
            <small class="info-danger">{{ $errors->general->first('supersedes_id') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="dropdown-wrap col-xl-4 {{ $errors->general->has('country_origin') ? ' dropdown-danger' : '' }}">
            <label>Country of Origin</label>
            <div class="dropdown-icon">
                <select name="country_origin" class="searchable" data-placeholder="Select Country">
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_origin', $model->country_origin) == $country->id || (!old('country_origin', $model->country_origin) && $country->name == 'Canada') ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->general->has('country_origin'))
            <small class="info-danger">{{ $errors->general->first('country_origin') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->general->has('country_shipped') ? ' dropdown-danger' : '' }}">
            <label>Country Shipped From</label>
            <div class="dropdown-icon">
                <select name="country_shipped" class="searchable js-country-shipped" data-placeholder="Select Country">
                    @foreach ($countries as $country)
                    <option value="{{ $country->id }}" {{ old('country_shipped', $model->country_shipped) == $country->id || (!old('country_shipped', $model->country_shipped) && $country->name == 'Canada') ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->general->has('country_shipped'))
            <small class="info-danger">{{ $errors->general->first('country_shipped') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 js-tariff {{ $errors->general->has('tariff_code') ? ' input-danger' : '' }}" {!! !old('country_shipped', $model->country_shipped) || old('country_shipped', $model->country_shipped) == 40 ? 'style="display: none;"' : ''
            !!}>
            <label>Tariff Code
                <div class="icon-input">
                    <i class="material-icons pre-icon">receipt</i>
                    <input type="text" name="tariff_code" value="{{ old('tariff_code', $model->tariff_code) }}">
                </div>
            </label>
            @if ($errors->general->has('tariff_code'))
            <small class="info-danger">{{ $errors->general->first('tariff_code') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="dropdown-wrap col-xl-4 {{ $errors->general->has('packaging_language') ? ' dropdown-danger' : '' }}">
            <label>Packaging Compliant Language</label>
            <div class="dropdown-icon">
                <select name="packaging_language" class="js-cat-field" data-cat-target="language">
                    @foreach ($model::PACKAGING_LANGUAGES as $value => $label)
                    <option value="{{ $value }}" {{ old('packaging_language', $model->packaging_language) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            @if ($errors->general->has('packaging_language'))
            <small class="info-danger">{{ $errors->general->first('packaging_language') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="dropdown-wrap js-product-category-container col-xl-4 {{ $errors->general->has('category_id') ? ' dropdown-danger' : '' }}">
            <label>Category</label>
            <div class="dropdown-icon">
                <select name="category_id" class="js-category">
                    @if (!old('category_id', $model->category_id))
                    <option value selected>Select Category</option>
                    @endif
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $model->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->general->has('category_id'))
            <small class="info-danger">{{ $errors->general->first('category_id') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->general->has('subcategory_id') ? ' dropdown-danger' : '' }}">
            <label>Subcategory</label>
            <div class="dropdown-icon">
                <select name="subcategory_id" class="js-sub-category">
                    @forelse ($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $model->subcategory_id) == $subcategory->id ? 'selected' : '' }}>
                        {{ $subcategory->name }}
                    </option>
                    @empty
                    <option value selected>Select Category First</option>
                    @endforelse
                </select>
            </div>
            @if ($errors->general->has('subcategory_id'))
            <small class="info-danger">{{ $errors->general->first('subcategory_id') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4{{ $errors->general->has('catalogue_category_id') ? ' dropdown-danger' : '' }}">
            <label>Catalogue Category</label>
            <div class="dropdown-icon">
                <select name="catalogue_category_id" class="js-cat-category js-cat-field" data-cat-target="category" data-cat-action="select">
                    <option value selected>Select Category</option>
                    @foreach ($catalogueCategories as $category)
                    <option data-cat-category-name-fr="{{ $category->name_fr }}" value="{{ $category->id }}" {{ old('catalogue_category_id', $model->catalogue_category_id) == $category->id ? 'selected' : '' }} >
                        {{ $category->name }}
                    </option>
                    @endforeach
                    <option value="0" {!! !old('catalogue_category_id', $model->catalogue_category_id) && (old('catalogue_category_proposal', $model->catalogue_category_proposal) || old('catalogue_category_proposal_fr', $model->catalogue_category_proposal_fr)) ? 'selected' : '' !!}>Add New Category</option>
                </select>
            </div>
            <small class="muted js-cat-category-fr">FR: <span></span></small>
            @if ($errors->general->has('catalogue_category_id'))
            <small class="info-danger">{{ $errors->general->first('catalogue_category_id') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4"></div>
        <div class="col-xl-4"></div>
        <div class="col-xl-4 js-new-cat-category" {!! !old('catalogue_category_id', $model->catalogue_category_id) && (old('catalogue_category_proposal', $model->catalogue_category_proposal) || old('catalogue_category_proposal_fr', $model->catalogue_category_proposal_fr)) ? '' : 'style="display: none;"' !!}>
            <div class="input-wrap {{ $errors->general->has('catalogue_category_proposal') ? ' input-danger' : '' }}">
                <label>New Catalogue Category</label>
                <div class="icon-input">
                    <i class="material-icons pre-icon">edit</i>
                    <input type="text" name="catalogue_category_proposal" class="js-category-proposal js-cat-field" data-cat-target="category" value="{{ old('catalogue_category_proposal', $model->catalogue_category_proposal) }}">
                </div>
                @if ($errors->general->has('catalogue_category_proposal'))
                <small class="info-danger">{{ $errors->general->first('catalogue_category_proposal') }}</small>
                @endif
            </div>
            <div class="input-wrap {{ $errors->general->has('catalogue_category_proposal_fr') ? ' input-danger' : '' }}">
                <label>New Catalogue Category (FR)</label>
                <div class="icon-input">
                    <i class="material-icons pre-icon">edit</i>
                    <input type="text" name="catalogue_category_proposal_fr" class="js-category-proposal-fr" value="{{ old('catalogue_category_proposal_fr', $model->catalogue_category_proposal_fr) }}">
                </div>
                @if ($errors->general->has('catalogue_category_proposal_fr'))
                <small class="info-danger">{{ $errors->general->first('catalogue_category_proposal_fr') }}</small>
                @endif
            </div>
        </div>
    </div>
</div>
