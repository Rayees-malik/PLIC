<input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
@if ($model->id)
<div class="row">
    <div class="col-xl-4">
        <h3>
            PAF ID #{{ $model->cloned_from_id ?? $model->id }}
        </h3>
    </div>
</div>
@endif

<div class="row">
    <div class="dropdown-wrap col-8 {{ $errors->header->has('accounts') ? 'dropdown-danger' : '' }}">
        <label>Accounts</label>
        <div class="dropdown-icon">
            <select name="accounts[]" class="searchable" multiple data-placeholder="Select Accounts">
                <option value></option>
                @foreach ($customers as $group => $accounts)
                @if ($accounts->count() > 1 && $group)
                <optgroup label="{{ $group }}">
                    @foreach ($accounts as $account)
                    <option value="{{ $account->customer_number }}" {{ in_array($account->customer_number, Arr::wrap(old('accounts', $model->accounts))) ? 'selected' : '' }}>{{ $account->name }} (#{{ $account->customer_number }})</option>
                    @endforeach
                </optgroup>
                @else
                @foreach ($accounts as $account)
                <option value="{{ $account->customer_number }}" {{ in_array($account->customer_number, Arr::wrap(old('accounts', $model->accounts))) ? 'selected' : '' }}>{{ $account->name }} (#{{ $account->customer_number }})</option>
                @endforeach
                @endif
                @endforeach
            </select>
        </div>
        @if ($errors->header->has('accounts'))
        <small class="info-danger">{{ $errors->header->first('accounts') }}</small>
        @endif
    </div>
</div>

<div class="row">
    <div class="input-wrap col-xl-4 {{ $errors->header->has('ongoing') ? 'input-danger' : '' }}">
        <label>Ongoing</label>
        <div class="inline-radio-group">
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" class="js-ongoing" name="ongoing" value="0" {{ !old('ongoing', $model->ongoing) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">No</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" class="js-ongoing" name="ongoing" value="1" {{ old('ongoing', $model->ongoing) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Yes</span>
                </label>
            </div>
        </div>
        @if ($errors->header->has('ongoing'))
        <small class="info-danger">{{ $errors->header->first('ongoing') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->header->has('dollar_discount') ? 'input-danger' : '' }}">
        <label>Discount Type</label>
        <div class="inline-radio-group">
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="dollar_discount" class="js-discount-type" value="0" {{ !old('dollar_discount', $model->dollar_discount) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Percent</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="dollar_discount" class="js-discount-type" value="1" {{ old('dollar_discount', $model->dollar_discount) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Fixed Price</span>
                </label>
            </div>
        </div>
        @if ($errors->header->has('dollar_discount'))
        <small class="info-danger">{{ $errors->header->first('dollar_discount') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-4 {{ $errors->header->has('dollar_mcb') ? 'input-danger' : '' }}">
        <label>MCB Type</label>
        <div class="inline-radio-group">
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="dollar_mcb" class="js-mcb-type" value="0" {{ !old('dollar_mcb', $model->dollar_mcb) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Percent</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="dollar_mcb" class="js-mcb-type" value="1" {{ old('dollar_mcb', $model->dollar_mcb) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Dollar</span>
                </label>
            </div>
        </div>
        @if ($errors->header->has('dollar_mcb'))
        <small class="info-danger">{{ $errors->header->first('dollar_mcb') }}</small>
        @endif
    </div>
</div>

<div class="row">
    <div class="input-wrap col-xl-4 {{ $errors->header->has('start_date') ? ' input-danger' : '' }}">
        <label>Start Date
            <div class="icon-input">
                <i class="material-icons pre-icon">calendar_today</i>
                <input type="text" name="start_date" class="js-datepicker" value="{{ old('start_date', $model->start_date) ?? (new \Carbon\Carbon('2 weekdays'))->format('Y-m-d') }}">
            </div>
        </label>
        @if ($errors->header->has('start_date'))
        <small class="info-danger">{{ $errors->header->first('start_date') }}</small>
        @endif
    </div>

    <div class="input-wrap col-xl-4 {{ $errors->header->has('end_date') ? ' input-danger' : '' }}">
        <label>End Date
            <div class="icon-input">
                <i class="material-icons pre-icon">calendar_today</i>
                <input type="hidden" name="end_date" class="js-ongoing-end-date no-history" value="{{ (date('m') >= 10 ? date('Y') + 1 : date('Y')) . '-12-31' }}">
                <input type="text" name="end_date" class="js-datepicker js-end-date" value="{{ old('end_date', $model->end_date) }}">
            </div>
        </label>
        @if ($errors->header->has('end_date'))
        <small class="info-danger">{{ $errors->header->first('end_date') }}</small>
        @endif
    </div>
</div>

<div class="row">
    <div class="input-wrap col-xl-6 {{ $errors->header->has('comment') ? ' input-danger' : '' }}">
        <label>MCB Authorization / Comment
            <textarea type="text" name="comment" autocomplete="off">{{
            old('comment', $model->comment)
    }}</textarea>
        </label>
        @if ($errors->header->has('comment'))
        <small class="info-danger">{{ $errors->header->first('comment') }}</small>
        @endif
    </div>
    @if (!$model->id || $model->submitted_by = auth()->id())
    <div class="input-wrap col-xl-6 {{ $errors->header->has('notes') ? ' input-danger' : '' }}">
        <label>Private Notes
            <textarea type="text" name="notes" autocomplete="off">{{
            old('notes', $model->notes)
    }}</textarea>
        </label>
        @if ($errors->header->has('notes'))
        <small class="info-danger">{{ $errors->header->first('notes') }}</small>
        @endif
    </div>
    @endif
</div>

<h3 class="form-section-title">Attachments</h3>
<div class="row">
    <div class="col-6">
        {!! BladeHelper::uploaderField($model, 'uploads', ['type' => 'local', 'limit' => null, 'extensions' => 'all', 'enabled' => true], false) !!}
    </div>
</div>

@if ($model->media->count())
<div class="row ml-3 mb-4">
    <div class="col">
        @foreach ($model->media as $media)
        {!! $media->getDownloadLink() !!}<br>
        @endforeach
    </div>
</div>
@endif

<h3 class="form-section-title">Add New</h3>
<div class="row">
    <div class="input-wrap col">
        @include('partials.product-picker')
    </div>
</div>

<h3 class="form-section-title">Adjustments</h3>
<div class="row mb-2">
    <div class="col-xl-4">
        <button type="button" class="secondary-btn" data-toggle="modal" title="Quick Update" data-target="#updateModal">
            <i class="material-icons">wifi_protected_setup</i>
            Quick Update
        </button>
    </div>
</div>
<div class="row">
    @include('pricingadjustments.line-item-table')
</div>

<div class="row mb-3">
  @if (isset($signoffForm) && Bouncer::can('signoff.paf.pricing'))
  <a href="{{ route('exports.pafupload', $model->id) }}" class="btn ml-5">Export Upload CSV</a>
  @endif
  @if (isset($signoffForm) && Bouncer::can('signoff.paf.pricing'))
  <a href="{{ route('exports.pafuploadwithmcb', $model->id) }}" class="btn ml-5">Export Upload With MCB CSV</a>
  @endif
</div>

@include('modals.js-delete')
@include('pricingadjustments.quick-update')

@push('scripts')
{!! BladeHelper::includeUploaders('local') !!}
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/modules/pricing-adjustments.js') }}"></script>
@endpush
