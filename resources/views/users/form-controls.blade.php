<input type="hidden" name="id" class="js-model-id" value="{{ $model->id }}">
<div class="input-wrap {{ $errors->has('name') ? ' input-danger' : '' }}">
    <label>Name
        <div class="icon-input">
            <i class="material-icons pre-icon">perm_identity</i>
            <input type="text" name="name" value="{{ old('name', $model->name) }}">
        </div>
    </label>
</div>
@error('name')
<small class="info-danger">{{ $message }}</small>
@enderror

<div class="input-wrap {{ $errors->has('email') ? ' input-danger' : '' }}">
    <label>Email
        <div class="icon-input">
            <i class="material-icons pre-icon">email</i>
            <input type="text" name="email" value="{{ old('email', $model->email) }}">
        </div>
    </label>
    @error('email')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('password') ? ' input-danger' : '' }}">
    <label>{{ $model->id ? 'Change' : 'Create' }} Password
        <div class="icon-input">
            <i class="material-icons pre-icon">vpn_key</i>
            <input type="password" name="password">
        </div>
        @if ($model->id) <small>Leave blank to keep current password</small> @endif
    </label>
    @error('password')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('password_confirmation') ? ' input-danger' : '' }}">
    <label>Confirm Password
        <div class="icon-input">
            <i class="material-icons pre-icon">vpn_key</i>
            <input type="password" name="password_confirmation">
        </div>
    </label>
    @error('password_confirmation')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

@if ($roleCategories->count())
<div class="dropdown-wrap {{ $errors->has('roles') ? ' dropdown-danger' : '' }}">
    <label>Roles</label>
    <div class="dropdown-icon">
        <select name="roles[]" class="searchable" multiple data-placeholder="Select Roles">
            @foreach ($roleCategories as $category => $roles)
            <optgroup label="{{ $category }}">
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" data-description="{{ $role->description }}" {{
                old('roles') ? (in_array($role->id, old('roles')) ? 'selected' : '' ) : ($model->isAn($role->name) ? 'selected' : '')
            }}>
                    {{ $role->title }}
                </option>
                @endforeach
            </optgroup>
            @endforeach
        </select>
    </div>
    @error('roles')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
@endif

@if (auth()->user()->isVendor && auth()->id() !== $model->id)
<div class="input-wrap {{ $errors->has('vendor_user_type') ? 'input-danger' : '' }}">
    <label>Access Type</label>
    <div class="inline-radio-group">
        <div class="radio-wrap">
            <label class="radio">
                <input type="radio" name="vendor_user_type" value="1" {{ old('vendor_user_type', $userType) == 1 ? 'checked' : '' }}>
                <span class="radio-checkmark"></span>
                <span class="radio-label">Regular</span>
            </label>
        </div>
        <div class="radio-wrap">
            <label class="radio">
                <input type="radio" name="vendor_user_type" value="0" {{ old('vendor_user_type', $userType) == 0 ? 'checked' : '' }}>
                <span class="radio-checkmark"></span>
                <span class="radio-label">Read-only</span>
            </label>
        </div>
        @if (Bouncer::can('vendor-finance'))
        <div class="radio-wrap">
            <label class="radio">
                <input type="radio" name="vendor_user_type" value="2" {{ !old('vendor_user_type', $userType) == 2 ? 'checked' : '' }}>
                <span class="radio-checkmark"></span>
                <span class="radio-label">Finance</span>
            </label>
        </div>
        @endif
    </div>
    @if ($errors->has('vendor_user_type'))
    <small class="info-danger">{{ $errors->first('vendor_user_type') }}</small>
    @endif
</div>
@elseif ($model->isVendor)
@if ($model->can('user.assign.broker') && !empty($brokers))
<div class="dropdown-wrap {{ $errors->has('broker_id') ? ' dropdown-danger' : '' }}">
    <label>Broker</label>
    <div class="dropdown-icon">
        <select name="broker_id" class="searchable" data-placeholder="Select Broker">
            <option value>No Broker Assigned</option>
            @foreach ($brokers as $broker)
            <option value="{{ $broker->id }}" @if (old('broker_id', $model->broker_id) == $broker->id) selected @endif>{{ $broker->name }}</option>
            @endforeach
        </select>
    </div>
    @error('broker_id')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
@elseif ($model->can('user.assign.vendor') && !empty($vendors))
<div class="dropdown-wrap {{ $errors->has('vendor_id') ? ' dropdown-danger' : '' }}">
    <label>Vendor</label>
    <div class="dropdown-icon">
        <select name="vendor_id" class="searchable" data-placeholder="Select Vendor">
            <option value>No Vendor Assigned</option>
            @foreach ($vendors as $vendor)
            <option value="{{ $vendor->id }}" @if (old('vendor_id', $model->vendor_id) == $vendor->id) selected @endif>{{ $vendor->name }}</option>
            @endforeach
        </select>
    </div>
    @error('vendor_id')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
@endif
@endif

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
