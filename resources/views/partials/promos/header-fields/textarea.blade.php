<div class="col-12 input-wrap {{ $errors->ownerHeader->has($field) ? ' input-danger' : '' }}">
    <label>{{ $fieldConfig['display'] }}
        <textarea class="{{ Arr::get($fieldConfig, 'classes') }}" name="{{ $field }}">{{ old($field, Arr::get($model->data, $field)) }}</textarea>
    </label>
    @if ($errors->ownerHeader->has($field))
    <small class="info-danger">{{ $errors->ownerHeader->first($field) }}</small>
    @endif
</div>
