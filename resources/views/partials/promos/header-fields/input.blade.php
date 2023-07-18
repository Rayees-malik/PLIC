<div class="col-xl-6 input-wrap {{ $errors->ownerHeader->has($field) ? ' input-danger' : '' }}">
    <label>{{ $fieldConfig['display'] }}
        <input type="text" class="{{ Arr::get($fieldConfig, 'classes') }}" name="{{ $field }}" value="{{ old($field, Arr::get($model->data, $field)) }}">
    </label>
    @if ($errors->ownerHeader->has($field))
    <small class="info-danger">{{ $errors->ownerHeader->first($field) }}</small>
    @endif
</div>
