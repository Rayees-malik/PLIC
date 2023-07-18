<div class="col-12 input-wrap {{ $errors->ownerHeader->has($field) ? ' input-danger' : '' }}">
    <label>{{ $fieldConfig['display'] }}</label>
    @foreach (Arr::get($fieldConfig, 'values') as $value)
    <div class="checkbox-wrap">
        <label class="checkbox {{ Arr::get($fieldConfig, 'classes') }}">
            <input type="checkbox" name="{{ $field }}{{ count(Arr::get($fieldConfig, 'values')) > 1 ? '[]' : '' }}" value="{{ $value }}" {{
               in_array($value, Arr::wrap(old($field, Arr::get($model->data, $field)))) ? 'checked' : '' }}>
            <span class="checkbox-checkmark"></span>
            <span class="checkbox-label">{{ $value }}</span>
        </label>
    </div>
    @endforeach
    @if ($errors->ownerHeader->has($field))
    <small class="info-danger">{{ $errors->ownerHeader->first($field) }}</small>
    @endif
</div>
