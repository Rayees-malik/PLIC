<div id="vendor" class="js-stepper-step stepper-step active">
    @include('partials.stepper.flash-error')

    <input type="hidden" name="id" class="js-model-id" value="{{ $model->id }}">
    <input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
    <h3 class="form-section-title">
        Vendor
        <span class="float-right">{{ implode(',', $brandNumbers) }}</span>
    </h3>
    <div class="row mb-4">
        <div class="input-wrap col-xl-4 {{ $errors->vendor->has("name") ? 'input-danger' : '' }}">
            <label>Name
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="name" value="{{ old('name', $model->name) }}">
                </div>
            </label>
            @if($errors->vendor->has('name'))
            <small class="info-danger">{{ $errors->vendor->first('name') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->vendor->has("phone") ? 'input-danger' : '' }}">
            <label>Phone Number
                <div class="icon-input">
                    <i class="material-icons pre-icon">local_phone</i>
                    <input type="text" name="phone" value="{{ old('phone', $model->phone) }}">
                </div>
            </label>
            @if($errors->vendor->has('phone'))
            <small class="info-danger">{{ $errors->vendor->first('phone') }}</small>
            @endif
        </div>

    </div>

    <h3 class="form-section-title">Address</h3>
    <div class="row">
        @include('partials.address-form', ['address' => $model->address ?? new App\Models\Address, 'errors' => optional($errors)->vendor])
    </div>
</div>
