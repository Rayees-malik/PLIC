<div id="brand" class="js-stepper-step stepper-step active">
    @include('partials.stepper.flash-error')
    <input type="hidden" name="id" class="js-model-id" value="{{ $model->id }}">
    <input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
    <h3 class="form-section-title">Brand</h3>
    <div class="row">
        @if (count($vendors) > 1)
        <div class="dropdown-wrap col-xl-8 {{ $errors->brand->has('vendor_id') ? 'dropdown-danger' : '' }}">
            <label>Vendor</label>
            <div class="dropdown-icon">
                <select name="vendor_id" id="vendor_id" class="searchable" data-placeholder="Select a Vendor">
                    @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->id }}" {{ old('vendor_id', $model->vendor_id) == $vendor->id ? 'selected' : '' }}>
                        {{ $vendor->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->brand->has('vendor_id'))
            <small class="info-danger">{{ $errors->brand->first('vendor_id') }}</small>
            @endif
        </div>
        @elseif ($vendors)
        <div class="col-xl-8">
            <label>Vendor</label>
            <h4 class="ml-3">{{ $vendors->first()->name }}</h4>
            <input type="hidden" name="vendor_id" value="{{ $vendors->first()->id }}">
        </div>
        @endif
    </div>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->brand->has('name') ? 'input-danger' : '' }}">
            <label>Name
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input id="name" class="js-cat-field" data-cat-target="name" type="text" name="name" autocomplete="off" value="{{ old('name', $model->name) }}">
                </div>
            </label>
            @if ($errors->brand->has('name'))
            <small class="info-danger">{{ $errors->brand->first('name') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->brand->has('name_fr') ? 'input-danger' : '' }}">
            <label>Name (FR)
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input id="name_fr" type="text" name="name_fr" autocomplete="off" value="{{ old('name_fr', $model->name_fr) }}">
                </div>
            </label>
            @if ($errors->brand->has('name_fr'))
            <small class="info-danger">{{ $errors->brand->first('name_fr') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="checkbox-wrap col-xl-4 mt-3">
            <label class="checkbox">
                <input type="hidden" name="made_in_canada" value="0">
                <input type="checkbox" name="made_in_canada" class="js-cat-field" data-cat-target="leaf" data-cat-action="visibility" value="1" {{ old('made_in_canada', $model->made_in_canada) ? 'checked' : '' }}>
                <span class="checkbox-checkmark"></span>
                <span class="checkbox-label">Made in Canada</span>
            </label>
        </div>
    </div>
    <div class="row">
        @if (auth()->user()->can('brand.edit.number'))
        <div class="input-wrap col-xl-4 {{ $errors->brand->has('brand_number') ? 'input-danger' : '' }}">
            <label>Brand Number
                <div class="icon-input">
                    <i class="material-icons pre-icon">looks_one</i>
                    <input id="brand_number" name="brand_number" value="{{ old('brand_number', $model->brand_number) }}">
                </div>
            </label>
            @if ($errors->brand->has('brand_number'))
            <small class="info-danger">{{ $errors->brand->first('brand_number') }}</small>
            @endif
        </div>
        @elseif ($model->brand_number)
        <div class="col-xl-4">
            <label class="mb-0">Brand Number</label>
            <h3 class="mb-2">{{ $model->brand_number }}</h3>
        </div>
        @endif
    </div>
    <div class="row">
        <div class="dropdown-wrap col-xl-8 {{ $errors->brand->has('brokers') ? 'dropdown-danger' : '' }}">
            <label>Select Broker(s)</label>
            <div class="dropdown-icon">
                <select class="searchable js-brokers" name="brokers[]" multiple data-placeholder="Select Broker(s)">
                    @foreach ($brokers as $broker)
                    <option value="{{ $broker->id }}" {{ in_array($broker->id, Arr::wrap(old('brokers', $model->brokers->pluck('id')->toArray()))) || $broker->id == auth()->user()->broker_id ? 'selected' : '' }}>{{
                        $broker->name
                    }}</option>
                    @endforeach
                </select>
            </div>
            @if ($errors->brand->has('brokers'))
            <small class="info-danger">{{ $errors->brand->first('brokers') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4">
            <label>Other Broker(s)
                <div class="icon-input">
                    <i class="material-icons pre-icon">edit</i>
                    <input type="text" name="broker_proposal" class="js-cat-field" data-cat-target="broker_proposed" value="{{ old('broker_proposal', $model->broker_proposal) }}">
                </div>
            </label>
        </div>
    </div>
    <div class="row">
        <div class="dropdown-wrap col-xl-4 {{ $errors->brand->has('currency_id') ? 'dropdown-danger' : '' }}">
            <label>Currency</label>
            <div class="dropdown-icon">
                <select name="currency_id">
                    @foreach ($currencies as $currency)
                    <option value="{{ $currency->id }}" {{
                        old('currency_id', $model->currency_id) == $currency->id || !old('currency_id', $model->currency_id) && $currency->name === 'CAD' ? 'selected' : ''
                    }}>
                        {{ $currency->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->brand->has('currency_id'))
            <small class="info-danger">{{ $errors->brand->first('currency_id') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->brand->has('website') ? 'input-danger' : '' }}">
            <label>Website
                <div class="icon-input">
                    <i class="material-icons pre-icon">language</i>
                    <input id="website" class="js-cat-field" data-cat-target="url" type="text" name="website" autocomplete="off" value="{{ old('website', $model->website) }}">
                </div>
            </label>

            @if ($errors->brand->has('website'))
            <small class="info-danger">{{ $errors->brand->first('website') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->brand->has('phone') ? 'input-danger' : '' }}">
            <label>Phone Number
                <div class="icon-input">
                    <i class="material-icons pre-icon">phone</i>
                    <input id="phone" class="js-cat-field" data-cat-target="tel" type="text" name="phone" autocomplete="off" value="{{ old('phone', $model->phone) }}">
                </div>
            </label>

            @if ($errors->brand->has('phone'))
            <small class="info-danger">{{ $errors->brand->first('phone') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->brand->has('description') ? 'input-danger' : '' }}">
            <label>Brand Description
                <textarea type="text" name="description" autocomplete="off" class="js-cat-field" data-cat-target="description">{{
                    old('description', $model->description)
                }}</textarea>
            </label>
            @if ($errors->brand->has('description'))
            <small class="info-danger">{{ $errors->brand->first('description') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->brand->has('description_fr') ? 'input-danger' : '' }}">
            <label>Brand Description (FR)
                <textarea type="text" name="description_fr" autocomplete="off">{{
                    old('description_fr', $model->description_fr)
                }}</textarea>
            </label>
            @if ($errors->brand->has('description_fr'))
            <small class="info-danger">{{ $errors->brand->first('description_fr') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->brand->has('unpublished_new_listing_deal') ? 'input-danger' : '' }}">
            <label>Unpublished New Listing Deal
                <textarea type="text" name="unpublished_new_listing_deal" autocomplete="off">{{
                    old('unpublished_new_listing_deal', $model->unpublished_new_listing_deal)
                }}</textarea>
            </label>
            @if ($errors->brand->has('unpublished_new_listing_deal'))
            <small class="info-danger">{{ $errors->brand->first('unpublished_new_listing_deal') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->brand->has('unpublished_new_listing_deal_fr') ? 'input-danger' : '' }}">
            <label>Unpublished New Listing Deal (FR)
                <textarea type="text" name="unpublished_new_listing_deal_fr" autocomplete="off">{{
                    old('unpublished_new_listing_deal_fr', $model->unpublished_new_listing_deal_fr)
                }}</textarea>
            </label>
            @if ($errors->brand->has('unpublished_new_listing_deal_fr'))
            <small class="info-danger">{{ $errors->brand->first('unpublished_new_listing_deal_fr') }}</small>
            @endif
        </div>
    </div>
</div>
