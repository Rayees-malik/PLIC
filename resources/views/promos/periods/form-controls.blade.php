<div class="row">
    <div class="input-wrap col-xl-8 {{ $errors->has('name') ? ' input-danger' : '' }}">
        <label>Period Name
            <div class="icon-input">
                <i class="material-icons pre-icon">timelapse</i>
                <input type="text" name="name" value="{{ old('name', $model->name) }}">
            </div>
        </label>
        @error('name')
        <small class="info-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
<div class="row">

    <div class="input-wrap col-xl-4 {{ $errors->has('start_date') ? ' input-danger' : '' }}">
        <label>Start Date
            <div class="icon-input">
                <i class="material-icons pre-icon">calendar_today</i>
                <input type="text" name="start_date" class="js-datepicker" value="{{ old('start_date', $model->start_date) }}">
            </div>
        </label>
        @error('start_date')
        <small class="info-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->has('end_date') ? ' input-danger' : '' }}">
        <label>End Date
            <div class="icon-input">
                <i class="material-icons pre-icon">calendar_today</i>
                <input type="text" name="end_date" class="js-datepicker" value="{{ old('end_date', $model->end_date) }}">
            </div>
        </label>
        @error('end_date')
        <small class="info-danger">{{ $message }}</small>
        @enderror
    </div>
    <div class="input-wrap col-xl-4">
        <label>Status</label>
        <div class="checkbox-wrap mt-3 mb-0">
            <label class="checkbox">
                <input type="hidden" class="no-history" name="active" value="0">
                <input type="checkbox" name="active" value="1" {{ old('active', $model->active === null || $model->active) ? 'checked' : '' }}>
                <span class="checkbox-checkmark"></span>
                <span class="checkbox-label">Active</span>
            </label>
        </div>
    </div>
</div>

<div class="row">
    <div class="dropdown-wrap col-xl-4 {{ $errors->has('type') ? ' dropdown-danger' : '' }}">
        <label>Period Type</label>
        <div class="dropdown-icon">
            <select name="type" class="js-period-type">
                @foreach (\App\Models\PromoPeriod::TYPES as $key => $type)
                <option value="{{ $key }}" {{ old('type', $model->type) == $key ? 'selected' : '' }}>
                    {{ $type }}
                </option>
                @endforeach
            </select>
        </div>
        @error('type')
        <small class="info-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="dropdown-wrap js-base-period col-xl-4 {{ $errors->has('base_period_id') ? ' dropdown-danger' : '' }}" @if (old('type', $model->type) != App\Models\PromoPeriod::LAYERED_TYPE) style="display:none" @endif>
        <label>Base Period</label>
        <div class="dropdown-icon">
            <select name="base_period_id">
                <option value>Select Period</option>
                @foreach ($basePeriods as $period)
                <option value="{{ $period->id }}" {{ old('base_period_id', $model->base_period_id) == $period->id ? 'selected' : '' }}>
                    {{ $period->name }}
                </option>
                @endforeach
            </select>
        </div>
        @error('base_period_id')
        <small class="info-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row js-orderform-header" @if (!old('type', $model->type) || old('type', $model->type) == App\Models\PromoPeriod::CATALOGUE_TYPE) style="display:none" @endif>
    <div class="input-wrap col-xl-8 {{ $errors->has('order_form_header') ? ' input-danger' : '' }}">
        <label>Order Form Header
            <div class="icon-input">
                <i class="material-icons pre-icon">edit</i>
                <input type="text" name="order_form_header" value="{{ old('order_form_header', $model->order_form_header) }}">
            </div>
        </label>
        @error('order_form_header')
        <small class="info-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
