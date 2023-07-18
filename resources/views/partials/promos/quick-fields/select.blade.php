<div class="row">
    <div class="dropdown-wrap col-8">
        <label>{{ $fieldConfig['display'] }}</label>
        <div class="dropdown-icon">
            <select class="js-quick-field" data-target="js-retailer-{{ $field }}" data-placeholder="Select {{ $fieldConfig['display'] }}">
                <option value=""></option>
                @foreach (Arr::get($fieldConfig, 'values') as $value)
                <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
