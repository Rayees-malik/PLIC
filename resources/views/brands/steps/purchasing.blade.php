<div id="purchasing" class="js-stepper-step stepper-step">
    @if (auth()->user()->can('edit', App\Models\Brand::class) && !auth()->user()->isVendor)
    <h3 class="form-section-title">Purchasing</h3>
    @if ($model->vendor)
    <div class="row">
        <div class="col-xl-4">
            <label class="mb-0">FOB Purity Distribution</label>
            <h4 class="mb-2">{{ $model->vendor->fob_purity_distribution_centres ? 'Yes' : 'No' }}</h4>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="dropdown-wrap col-xl-4 {{ $errors->purchasing->has('purchasing_specialist_id') ? 'dropdown-danger' : '' }}">
            <label>Purchasing Specialist</label>
            <div class="dropdown-icon">
                <select name="purchasing_specialist_id" class="searchable" data-placeholder="Select a Specialist">
                    <option selected disabled></option>
                    @foreach (App\User::whereIs('purchasing-specialist')->get() as $prs)
                    <option value="{{ $prs->id }}" {{ old('purchasing_specialist_id', $model->purchasing_specialist_id) == $prs->id ? 'selected' : '' }}>
                        {{ $prs->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->purchasing->has('purchasing_specialist_id'))
            <small class="info-danger">{{ $errors->purchasing->first('purchasing_specialist_id') }}</small>
            @endif
        </div>

        <div class="dropdown-wrap col-xl-4 {{ $errors->purchasing->has('vendor_relations_specialist_id') ? 'dropdown-danger' : '' }}">
            <label>Vendor Relations Specialist</label>
            <div class="dropdown-icon">
                <select name="vendor_relations_specialist_id" class="searchable" data-placeholder="Select a Specialist">
                    <option selected disabled></option>
                    @foreach (App\User::whereIs('vendor-relations-specialist')->get() as $vrs)
                    <option value="{{ $vrs->id }}" {{ old('vendor_relations_specialist_id', $model->vendor_relations_specialist_id) == $vrs->id ? 'selected' : '' }}>
                        {{ $vrs->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->purchasing->has('vendor_relations_specialist_id'))
            <small class="info-danger">{{ $errors->purchasing->first('vendor_relations_specialist_id') }}</small>
            @endif
        </div>
    </div>
    @elseif ($model->purchasingSpecialist || $model->vendorRelationsSpecialist)
    <h3 class="form-section-title">Purchasing</h3>
    <div class="row">
        @if ($model->purchasing_specialist_id)
        <div class="col-xl-4">
            <label class="mb-0">Purchasing Specialist</label>
            <h3 class="mb-2">{{ $model->purchasingSpecialist->name }}</h3>
        </div>
        @endif
        @if ($model->vendorRelationsSpecialist)
        <div class="col-xl-4">
            <label class="mb-0">Vendor Relations Specialist</label>
            <h3 class="mb-2">{{ $model->vendorRelationsSpecialist->name }}</h3>
        </div>
        @endif
    </div>
    @endif

    <h3 class="form-section-title mt-3">
        Ordering
        <small>*as per distribution agreement</small>
    </h3>
    <div class="row">
        <div class="dropdown-wrap input-wrap col-xl-4 {{ $errors->purchasing->has("minimum_order_quantity") ? 'dropdown-danger input-danger' : '' }}">
            <label>Minimum Order Quantity</label>
            <div class="dropdown-icon" style="display: inline-block; width: 24%; top: 1px">
                <select name="minimum_order_type">
                    <option value="$" {{ old('minimum_order_type', $model->minimum_order_type) == '$' ? 'selected' : '' }}>$</option>
                    <option value="#" {{ old('minimum_order_type', $model->minimum_order_type) == '#' ? 'selected' : '' }}>#</option>
                </select>
            </div>

            <input name="minimum_order_quantity" value="{{ old('minimum_order_quantity', $model->minimum_order_quantity) }}" style="width: 74%">
            @if ($errors->purchasing->has('minimum_order_quantity'))
            <small class="info-danger">{{ $errors->purchasing->first('minimum_order_quantity') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->purchasing->has("shipping_lead_time") ? 'input-danger' : '' }}">
            <label>Shipping Lead Time
                <div class="icon-input">
                    <i class="material-icons pre-icon">update</i>
                    <input type="text" name="shipping_lead_time" value="{{ old('shipping_lead_time', $model->shipping_lead_time) }}">
                </div>
            </label>
            @if ($errors->purchasing->has('shipping_lead_time'))
            <small class="info-danger">{{ $errors->purchasing->first('shipping_lead_time') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->purchasing->has("product_availability") ? 'input-danger' : '' }}">
            <label>Product Availability
                <div class="icon-input">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input name="product_availability" class="js-datepicker" value="{{ old('product_availability', $model->product_availability) }}">
                </div>
            </label>
            @if ($errors->purchasing->has('product_availability'))
            <small class="info-danger">{{ $errors->purchasing->first('product_availability') }}</small>
            @endif
        </div>
    </div>
</div>
