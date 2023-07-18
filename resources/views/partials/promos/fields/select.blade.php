<td>
    <div class="dropdown-wrap {{ $errors->ownerProducts->has("{$field}.{$product->id}") ? ' dropdown-danger' : '' }}">
        <div class="dropdown-icon">
            <select name="{{ $field }}[{{ $product->id }}]" class="js-retailer-{{ $field }} {{ Arr::get($fieldConfig, 'classes') }}" data-placeholder="Select {{ $fieldConfig['display'] }}">
                @foreach (Arr::get($fieldConfig, 'values') as $value)
                <option value="{{ $value }}" {{
                  old("{$field}.{$product->id}", $model->id ? Arr::get($product->getPromoLineItem($model->period_id)->data, $field) : null) == $value ? 'selected' : ''
                }}>{{ $value }}</option>
                @endforeach
            </select>
        </div>
        @if ($errors->ownerProducts->has("{$field}.{$product->id}"))
        <small class="info-danger">{{ $errors->ownerProducts->first("{$field}.{$product->id}") }}</small>
        @endif
    </div>
</td>
