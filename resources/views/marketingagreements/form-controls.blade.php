<input type="hidden" name="updated_at" value="{{ $model->updated_at }}">
<div class="row">
    <div class="dropdown-wrap col-6 {{ $errors->header->has('account') ? 'dropdown-danger' : '' }}">
        <label>Account</label>
        <div class="dropdown-icon">
            <select name="account" class="searchable js-account" data-placeholder="Select Account">
                <option value="Other">Other</option>
                @foreach ($customers as $customer)
                <option value="{{ $customer->customer_number }}" {{ old('account', $model->account) == $customer->customer_number ? 'selected' : '' }}>{{ $customer->name }} (#{{ $customer->customer_number }})</option>
                @endforeach
            </select>
        </div>
        @if ($errors->header->has('account'))
        <small class="info-danger">{{ $errors->header->first('account') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-6 js-account-other-row {{ $errors->header->has('account_other') ? ' input-danger' : '' }}" style="{{ old('account', $model->account) == 'Other' || old('account', $model->account) == '' ? '' : 'display: none;' }}">
        <label>Other
            <input name="account_other" value="{{ old('account_other', $model->account_other) }}">
        </label>
        @if ($errors->header->has('account_other'))
        <small class="info-danger">{{ $errors->header->first('account_other') }}</small>
        @endif
    </div>
</div>
<div class="row">
    <div class="dropdown-wrap col-6 {{ $errors->header->has('send_to') ? 'dropdown-danger' : '' }}">
        <label>Send To</label>
        <div class="dropdown-icon">
            <select name="send_to" class="searchable" data-placeholder="Select User">
                <option></option>
                @foreach ($sendToUsers as $user)
                <option value="{{ $user->id }}" {{ old('send_to', $model->send_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        @if ($errors->header->has('send_to'))
        <small class="info-danger">{{ $errors->header->first('send_to') }}</small>
        @endif
    </div>
</div>
<div class="row">
    <div class="input-wrap col-xl-6 {{ $errors->header->has('ship_to_number') ? ' input-danger' : '' }}">
        <label>Ship To #
            <input name="ship_to_number" value="{{ old('ship_to_number', $model->ship_to_number) }}">
        </label>
        @if ($errors->header->has('ship_to_number'))
        <small class="info-danger">{{ $errors->header->first('ship_to_number') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-6 {{ $errors->header->has('retailer_invoice') || $duplicateInvoice ? ' input-danger' : '' }}">
        <label>Retailer Invoice #
            <input name="retailer_invoice" value="{{ old('retailer_invoice', $model->retailer_invoice) }}">
        </label>
        @if ($errors->header->has('retailer_invoice'))
        <small class="info-danger">{{ $errors->header->first('retailer_invoice') }}</small>
        @endif
        @if ($duplicateInvoice)
        <small class="info-danger">Duplicate invoice</small>
        @endif
    </div>
</div>

<div class="row">
    <div class="input-wrap col-xl-6 {{ $errors->header->has('comment') ? ' input-danger' : '' }}">
        <label>Comment
            <textarea type="text" name="comment" autocomplete="off">{{
            old('comment', $model->comment)
    }}</textarea>
        </label>
        @if ($errors->header->has('comment'))
        <small class="info-danger">{{ $errors->header->first('comment') }}</small>
        @endif
    </div>
    <div class="input-wrap col-xl-6 {{ $errors->header->has('approval_email') ? ' input-danger' : '' }}">
        <label>Approval Email <small style="display: inline; font-style: italic;">(copy&paste the email content here)</small>
            <textarea type="text" name="approval_email" autocomplete="off" style="font-size: 10px;">{{
            old('approval_email', $model->approval_email)
    }}</textarea>
        </label>
        @if ($errors->header->has('approval_email'))
        <small class="info-danger">{{ $errors->header->first('approval_email') }}</small>
        @endif
    </div>
</div>


@if (isset($signoffForm) && Bouncer::can('signoff.maf.accounting'))
<h3 class="form-section-title">Accounting</h3>
<div class="row mb-4">
    <div class="col">
        <a href="{{ route('exports.mafjournal', $model->id) }}" class="btn ml-3">Export Marketing Agreement Journal</a><br>
        @foreach ($lineItemBrands as $brandId => $brandName)
        <a href="{{ route('exports.mafchargeback', ['id' => $model->id, 'brandId' => $brandId]) }}" class="btn ml-3 mt-3">Export MCB Charge Back for {{ $brandName }}</a>
        @endforeach
    </div>
</div>
@endif

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

<div class="row">
    @include('marketingagreements.line-item-table')
</div>
@include('modals.js-delete')

@push('scripts')
{!! BladeHelper::includeUploaders('local') !!}
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/modules/marketing-agreements.js') }}"></script>
@endpush
