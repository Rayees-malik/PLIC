<div class="input-wrap {{ $errors->has('name') ? ' input-danger' : '' }}">
    <label>Country
        <div class="icon-input">
            <i class="material-icons pre-icon">flag</i>
            <input type="text" name="name" value="{{ old('name', $model->name) }}">
        </div>
    </label>
    @error('name')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('alpha2') ? ' input-danger' : '' }}">
    <label>Alpha-2 Code
        <div class="icon-input">
            <i class="material-icons pre-icon">looks_2</i>
            <input type="text" name="alpha2" value="{{ old('alpha2', $model->alpha2) }}">
        </div>
    </label>
    @error('alpha2')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('alpha3') ? ' input-danger' : '' }}">
    <label>Alpha-3 Code
        <div class="icon-input">
            <i class="material-icons pre-icon">looks_3</i>
            <input type="text" name="alpha3" value="{{ old('alpha3', $model->alpha3) }}">
        </div>
    </label>
    @error('alpha3')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
