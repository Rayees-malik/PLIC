@if ($periods->count() > 1)
<div class="dropdown-icon">
    <select name="period_id" class="js-period-id searchable" data-placeholder="Select Period">
        @foreach ($periods as $period)
        <option value="{{ $period->id }}" {{ old('period_id', isset($model) ? $model->period_id : null) == $period->id ? 'selected' : '' }}>
            {{ $period->name }} ({{ $period->dateRange }})
        </option>
        @endforeach
    </select>
</div>
@if ($errors->header->has('period_id'))
<small class="info-danger">{{ $errors->header->first('period_id') }}</small>
@endif
@else
<h2 class="mb-0">{{ $periods->first()->name }}</h2>
@if (!isset($model) ||$model->id)
<input type="hidden" name="period_id" class="js-period-id" value="{{ $periods->first()->id }}">
@endif
@endif
