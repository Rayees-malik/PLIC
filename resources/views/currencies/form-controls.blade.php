<div class="input-wrap {{ $errors->has('name') ? ' input-danger' : '' }}">
    <label>Currency
        <div class="icon-input">
            <i class="material-icons pre-icon">money</i>
            <input type="text" name="name" value="{{ old('name', $model->name) }}">
        </div>
    </label>
    @error('name')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('exchange_rate') ? ' input-danger' : '' }}">
    <label>Exchange Rate
        <div class="icon-input">
            <i class="material-icons pre-icon">local_atm</i>
            <input type="text" name="exchange_rate" value="{{ old('exchange_rate', $model->exchange_rate) }}">
        </div>
    </label>
    @error('exchange_rate')
        <small class="info-danger">{{ $message }}</small>
    @enderror
</div>
