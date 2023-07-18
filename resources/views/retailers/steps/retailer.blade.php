<div id="retailer" class="js-stepper-step stepper-step">
    @include('partials.stepper.flash-error')
    <input type="hidden" name="id" class="js-model-id" value="{{ $model->id }}">
    <input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
    <h3 class="form-section-title">Retailer</h3>
    <div class="row">
        <div class="input-wrap col-xl-8 {{ $errors->retailer->has('name') ? ' input-danger' : '' }}">
            <label>Retailer Name
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="name" value="{{ old('name', $model->name) }}">
                </div>
            </label>
            @if ($errors->retailer->has('name'))
            <small class="info-danger">{{ $errors->retailer->first('name') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->retailer->has('number_stores') ? ' input-danger' : '' }}">
            <label># Stores
                <div class="icon-input">
                    <i class="material-icons pre-icon">store</i>
                    <input type="text" name="number_stores" value="{{ old('number_stores', $model->number_stores) }}">
                </div>
            </label>
            @if ($errors->retailer->has('number_stores'))
            <small class="info-danger">{{ $errors->retailer->first('number_stores') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->retailer->has('fiscal_year_start') ? ' input-danger' : '' }}">
            <label>Start of Fiscal Year
                <div class="icon-input calendar-dropdown">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input type="text" name="fiscal_year_start" class="js-datepicker" value="{{ old('fiscal_year_start', $model->fiscal_year_start) }}">
                </div>
            </label>
            @if ($errors->retailer->has('fiscal_year_start'))
            <small class="info-danger">{{ $errors->retailer->first('fiscal_year_start') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->retailer->has('account_manager_id') ? 'dropdown-danger' : '' }}">
            <label>Key Account Manager</label>
            <div class="dropdown-icon">
                <select name="account_manager_id" class="searchable" data-placeholder="Select Manager">
                    <option selected disabled></option>
                    @foreach ($accountManagers as $manager)
                    <option value="{{ $manager->id }}" {{ old('account_manager_id', $model->account_manager_id) == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->retailer->has('vendor_relations_specialist_id'))
            <small class="info-danger">{{ $errors->retailer->first('vendor_relations_specialist_id') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->retailer->has('markup') ? ' input-danger' : '' }}">
            <label>Markup
                <div class="icon-input">
                    <i class="pre-icon">%</i>
                    <input name="markup" value="{{ old('markup', $model->markup) }}">
                </div>
            </label>
            @if ($errors->retailer->has('markup'))
            <small class="info-danger">{{ $errors->retailer->first('markup') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->retailer->has('target_margin') ? ' input-danger' : '' }}">
            <label>Target Margin
                <div class="icon-input">
                    <i class="pre-icon">%</i>
                    <input name="target_margin" value="{{ old('target_margin', $model->target_margin) }}">
                </div>
            </label>
            @if ($errors->retailer->has('target_margin'))
            <small class="info-danger">{{ $errors->retailer->first('target_margin') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->retailer->has('as400_pricing_file') ? ' input-danger' : '' }}">
            <label>AS400 Pricing File
                <div class="icon-input">
                    <i class="material-icons pre-icon">insert_drive_file</i>
                    <input type="text" name="as400_pricing_file" value="{{ old('as400_pricing_file', $model->as400_pricing_file) }}">
                </div>
            </label>
            @if ($errors->retailer->has('as400_pricing_file'))
            <small class="info-danger">{{ $errors->retailer->first('as400_pricing_file') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-8 {{ $errors->retailer->has('distribution_type') ? ' input-danger' : '' }}">
            <label>Distribution Type
                <textarea type="text" name="distribution_type" autocomplete="off" style="resize: none">{{
                    old('distribution_type', $model->distribution_type)
                }}</textarea>
            </label>
            @if ($errors->retailer->has('distribution_type'))
            <small class="info-danger">{{ $errors->retailer->first('distribution_type') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->retailer->has('distributors') ? 'dropdown-danger' : '' }}">
            <label>Select Distributor(s)</label>
            <div class="dropdown-icon">
                <select class="searchable" name="distributors[]" multiple data-placeholder="Select Distributor(s)">
                    @foreach ($distributors as $distributor)
                    <option value="{{ $distributor->id }}" {{ in_array($distributor->id, Arr::wrap(old('distributors', $model->distributors->pluck('id')->toArray()))) ? 'selected' : '' }}>{{
                    $distributor->name
                    }}</option>
                    @endforeach
                </select>
            </div>
            @if ($errors->retailer->has('distributors'))
            <small class="info-danger">{{ $errors->retailer->first('distributors') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="vertical-radio-group col-xl-4">
            <label>Costing Type</label>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="costing_type" value="landed" {{ old('costing_type', $model->costing_type) != 'warehouse' ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Fixed Landed</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="costing_type" value="Warehouse" {{ old('costing_type', $model->costing_type) == 'warehouse' ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Warehouse</span>
                </label>
            </div>
        </div>
        <div class="vertical-radio-group col-xl-4">
            <label>Warehouse Number</label>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="warehouse_number" value="01" {{ !in_array(old('warehouse_number', $model->warehouse_number), ['04', '08']) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">01</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="warehouse_number" value="04" {{ old('warehouse_number', $model->warehouse_number) == '04' ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">04</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="warehouse_number" value="08" {{ old('warehouse_number', $model->warehouse_number) == '08' ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">08</span>
                </label>
            </div>
        </div>
        <div class="vertical-radio-group col-xl-4">
            <label>Allow Promos</label>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="allow_promos" value="1" {{ old('allow_promos', $model->allow_promos) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Yes</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="allow_promos" value="0" {{ !old('allow_promos', $model->allow_promos) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">No</span>
                </label>
            </div>
        </div>
    </div>

    <h3 class="form-section-title">Head Office Address</h3>
    <div class="row">
        @include('partials.address-form', ['address' => $model->address ?? new App\Models\Address, 'errors' => optional($errors)->vendor])
    </div>
</div>
