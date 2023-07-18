<td>
    <div class="input-wrap {{ $errors->ownerProducts->has("{$field}.{$product->id}") ? ' input-danger' : '' }}">
        <input type="text" class="js-retailer-{{ $field }} {{ Arr::get($fieldConfig, 'classes') }}" name="{{ $field }}[{{ $product->id }}]" value="{{
          old("{$field}.{$product->id}", $model->id ? Arr::get($product->getPromoLineItem($model->period_id)->data, $field) : '') }}">
        @if ($errors->ownerProducts->has("{$field}.{$product->id}"))
        <small class="info-danger">{{ $errors->ownerProducts->first("{$field}.{$product->id}") }}</small>
        @endif
    </div>
</td>
