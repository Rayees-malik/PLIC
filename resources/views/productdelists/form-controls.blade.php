<input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
<div class="row">
    <div class="col-12">
        <h3>{{ $model->product->getName() }} (#{{ $model->product->stock_id }})</h3>
    </div>
</div>

<div class="row">
    <div class="input-wrap col {{ $errors->has('reason') ? ' input-danger' : '' }}">
        <label>Delist Reason
            <div class="input">
                <textarea type="text" name="reason" autocomplete="off">{{
                old('reason', $model->reason)
            }}</textarea>
            </div>
        </label>
        @if ($errors->has('reason'))
        <small class="info-danger">{{ $errors->first('reason') }}</small>
        @endif
    </div>
</div>
