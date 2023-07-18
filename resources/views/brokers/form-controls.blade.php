<div class="input-wrap{{ $errors->has('name') ? ' input-danger' : '' }}">
    <label>Name
        <div class="icon-input">
            <i class="material-icons pre-icon">account_box</i>
            <input type="text" name="name" value="{{ old('name', $model->name) }}" required autofocus>
        </div>
    </label>
    @error('name')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
<div class="input-wrap {{ $errors->has('brands') ? ' input-danger' : '' }}">
    <label>Brands
        <div class="icon-input">
            <select name="brands[]" class="searchable" multiple data-placeholder="Select Brands">
                @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{
                    in_array($brand->id, Arr::wrap(old('brands', $model->brands->contains($brand->id) ? $brand->id : null))) ? 'selected' : ''
                }}>
                    {{ $brand->name }}
                </option>
                @endforeach
            </select>
        </div>
    </label>
    @error('brands')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
