<td>
    <div class="input-wrap {{ $errors->ownerProducts->has("{$field}.{$product->id}") ? ' input-danger' : '' }}">
        @foreach (Arr::get($fieldConfig, 'values') as $value)
        <div class="checkbox-wrap">
            <label class="checkbox {{ Arr::get($fieldConfig, 'classes') }}">
                <input type="checkbox" name="{{ $field }}[{{ $product->id }}]{{ count(Arr::get($fieldConfig, 'values')) > 1 ? '[]' : '' }}" value="{{ $value }}" class="js-retailer-{{ $field }}" {{
                  in_array($value, Arr::wrap(old("{$field}.{$product->id}", $model->id ? Arr::get($product->getPromoLineItem($model->period_id)->data, $field) : null))) ? 'checked' : ''
                }}>
                <span class="checkbox-checkmark"></span>
                <span class="checkbox-label">{{ $value }}</span>
            </label>
        </div>
        @endforeach
        @if ($errors->ownerProducts->has("{$field}.{$product->id}"))
        <small class="info-danger">{{ $errors->ownerProducts->first("{$field}.{$product->id}") }}</small>
        @endif
    </div>
</td>
