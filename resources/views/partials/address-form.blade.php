<div class="input-wrap col-xl-4 {{ $errors->has('address') ? 'input-danger' : '' }}">
    <label>Address
        <div class="icon-input">
            <i class="material-icons pre-icon">location_on</i>
            <input type="text" name="address" value="{{ old('address', $address->address) }}">
        </div>
    </label>
    @if ($errors->has('address'))
    <small class="info-danger">{{ $errors->first('address') }}</small>
    @endif
</div>

<div class="input-wrap col-xl-4 {{ $errors->has('address2') ? 'input-danger' : '' }}">
    <label>Address 2
        <div class="icon-input">
            <i class="material-icons pre-icon">location_on</i>
            <input type="text" name="address2" value="{{ old('address2', $address->address2) }}">
        </div>
    </label>
    @if ($errors->has('address2'))
    <small class="info-danger">{{ $errors->first('address2') }}</small>
    @endif
</div>

<div class="input-wrap col-xl-4 {{ $errors->has('city') ? 'input-danger' : '' }}">
    <label>City
        <div class="icon-input">
            <i class="material-icons pre-icon">location_city</i>
            <input type="text" name="city" value="{{ old('city', $address->city) }}">
        </div>
    </label>
    @if ($errors->has('city'))
    <small class="info-danger">{{ $errors->first('city') }}</small>
    @endif
</div>

<div class="input-wrap col-xl-4 {{ $errors->has('province') ? 'input-danger' : '' }}">
    <label>Province/State
        <div class="icon-input">
            <i class="material-icons pre-icon">map</i>
            <input type="text" name="province" value="{{ old('province', $address->province) }}">
        </div>
    </label>
    @if ($errors->has('province'))
    <small class="info-danger">{{ $errors->first('province') }}</small>
    @endif
</div>

<div class="input-wrap col-xl-4 {{ $errors->has('postal_code') ? 'input-danger' : '' }}">
    <label>Postal Code/Zip Code
        <div class="icon-input">
            <i class="material-icons pre-icon">my_location</i>
            <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}">
        </div>
    </label>
    @if ($errors->has('postal_code'))
    <small class="info-danger">{{ $errors->first('postal_code') }}</small>
    @endif
</div>

<div class="dropdown-wrap col-xl-4 {{ $errors->has('country_id') ? 'dropdown-danger' : '' }}">
    <label>Country </label>
    <div class="dropdown-icon">
        <select name="country_id" class="searchable" data-placeholder="Select Country">
            <option value="">Select...</option>
            @foreach ($countries as $country)
            <option value="{{ $country->id }}" {{ old('country_id', $address->country_id) == $country->id ? 'selected' : '' }}>
                {{ ucfirst($country->name) }}
            </option>
            @endforeach
        </select>
    </div>
    @if ($errors->has('country_id'))
    <small class="info-danger">{{ $errors->first('country_id') }}</small>
    @endif
</div>
