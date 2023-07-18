<div class="row">
    <div class="input-wrap col-8">
        <label>{{ $fieldConfig['display'] }}</label>
        @foreach (Arr::get($fieldConfig, 'values') as $value)
        <div class="checkbox-wrap">
            <label class="checkbox">
                <input type="checkbox" value="{{ $value }}" class="js-quick-field" data-target="js-retailer-{{ $field }}">
                <span class="checkbox-checkmark"></span>
                <span class="checkbox-label">{{ $value }}</span>
            </label>
        </div>
        @endforeach
    </div>
</div>
