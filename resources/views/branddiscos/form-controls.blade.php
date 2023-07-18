<input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
<div class="row">
    <div class="col-xl-4">
        <label>Brand
            <h3>{{ $model->brand->name }} ({{ $model->brand->brand_number }})</h3>
        </label>
    </div>
    <div class="col-xl-4">
        <label>Category Code
            <h3>{{ $model->brand->category_code }}</h3>
        </label>
    </div>
</div>

<div class="row">
    <div class="input-wrap col {{ $errors->has('reason') ? ' input-danger' : '' }}">
        <label>Disco Reason
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
<div class="row">
    <div class="input-wrap col {{ $errors->has('recoup_plan') ? ' input-danger' : '' }}">
        <label>Plan to Recoup $
            <div class="input">
                <textarea type="text" name="recoup_plan" autocomplete="off">{{
                  old('recoup_plan', $model->recoup_plan)
              }}</textarea>
            </div>
        </label>
        @if ($errors->has('recoup_plan'))
        <small class="info-danger">{{ $errors->first('recoup_plan') }}</small>
        @endif
    </div>
</div>
<div class="row">
    <div class="input-wrap col-xl-6 {{ $errors->has('ap_owed') ? ' input-danger' : '' }}">
        <label>A/P Owed
            <div class="icon-input">
                <i class="material-icons pre-icon">receipt</i>
                <input type="text" name="ap_owed" value="{{ old('ap_owed', $model->ap_owed) }}">
            </div>
        </label>
        @if ($errors->has('ap_owed'))
        <small class="info-danger">{{ $errors->first('ap_owed') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-6 {{ $errors->has('inventory_value') ? ' input-danger' : '' }}">
        <label>Value of On-Hand Inventory
            <div class="icon-input">
                <i class="material-icons pre-icon">pan_tool</i>
                <input type="text" name="inventory_value" value="{{ old('inventory_value', $model->inventory_value) }}">
            </div>
        </label>
        @if ($errors->has('inventory_value'))
        <small class="info-danger">{{ $errors->first('inventory_value') }}</small>
        @endif
    </div>
</div>
<div class="row">
    <div class="input-wrap col-xl-6 {{ $errors->has('ytd_sales') ? ' input-danger' : '' }}">
        <label>YTD Sales
            <div class="icon-input">
                <i class="material-icons pre-icon">attach_money</i>
                <input type="text" name="ytd_sales" value="{{ old('ytd_sales', $model->ytd_sales) }}">
            </div>
        </label>
        @if ($errors->has('ytd_sales'))
        <small class="info-danger">{{ $errors->first('ytd_sales') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-6 {{ $errors->has('ytd_margin') ? ' input-danger' : '' }}">
        <label>YTD Margin
            <div class="icon-input">
                <i class="pre-icon">%</i>
                <input type="text" name="ytd_margin" value="{{ old('ytd_margin', $model->ytd_margin) }}">
            </div>
        </label>
        @if ($errors->has('ytd_margin'))
        <small class="info-danger">{{ $errors->first('ytd_margin') }}</small>
        @endif
    </div>
</div>
<div class="row">
    <div class="input-wrap col-xl-6 {{ $errors->has('previous_year_sales') ? ' input-danger' : '' }}">
        <label>Previous Year Sales
            <div class="icon-input">
                <i class="material-icons pre-icon">attach_money</i>
                <input type="text" name="previous_year_sales" value="{{ old('previous_year_sales', $model->previous_year_sales) }}">
            </div>
        </label>
        @if ($errors->has('previous_year_sales'))
        <small class="info-danger">{{ $errors->first('previous_year_sales') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-6 {{ $errors->has('previous_year_margin') ? ' input-danger' : '' }}">
        <label>Previous Year Margin
            <div class="icon-input">
                <i class="pre-icon">%</i>
                <input type="text" name="previous_year_margin" value="{{ old('previous_year_margin', $model->previous_year_margin) }}">
            </div>
        </label>
        @if ($errors->has('previous_year_margin'))
        <small class="info-danger">{{ $errors->first('previous_year_margin') }}</small>
        @endif
    </div>
</div>
