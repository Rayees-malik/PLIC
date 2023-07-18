<div class="input-wrap {{ $errors->has('unit') ? ' input-danger' : '' }}">
    <label>Unit
        <div class="icon-input">
            <i class="material-icons pre-icon">hourglass_empty</i>
            <input type="text" name="unit" value="{{ old('unit', $model->unit) }}">
        </div>
    </label>
    @error('unit')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('description') ? ' input-danger' : '' }}">
    <label>Description
        <div class="icon-input">
            <i class="material-icons pre-icon">subject</i>
            <input type="text" name="description" value="{{ old('description', $model->description) }}">
        </div>
    </label>
    @error('description')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
