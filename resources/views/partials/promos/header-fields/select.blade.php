<div class="col-xl-6 dropdown-wrap {{ $errors->ownerHeader->has($field) ? ' dropdown-danger' : '' }}">
    <label>{{ $fieldConfig['display'] }}</label>
    <div class="dropdown-icon">
        <select name="{{ $field }}" class="{{ Arr::get($fieldConfig, 'classes') }}" data-placeholder="Select {{ $fieldConfig['display'] }}">
            @foreach (Arr::get($fieldConfig, 'values') as $value)
            <option value="{{ $value }}" {{ old($field, Arr::get($model->data, $field)) == $value ? 'selected' : '' }}>
                {{ $value }}
            </option>
            @endforeach
        </select>
    </div>
    @if ($errors->ownerHeader->has($field))
    <small class="info-danger">{{ $errors->ownerHeader->first($field) }}</small>
    @endif
</div>
