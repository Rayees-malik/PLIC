<input type="hidden" name="updated_at" value="{{ $model->updated_at }}">

@if (isset($signoffForm))
<h4 class="text-center">
    Note: Per Finance - Please do not remove stock the last two business days of the Month
</h4>
@endif

<div class="row">
    @if (!isset($signoffForm))
    <div class="input-wrap col-xl-12 {{ $errors->header->has('comment') ? ' input-danger' : '' }}">
        <label>Comment
            <textarea type="text" name="comment" autocomplete="off">{{
            old('comment', $model->comment)
    }}</textarea>
        </label>
        @if ($errors->header->has('comment'))
        <small class="info-danger">{{ $errors->header->first('comment') }}</small>
        @endif
    </div>
    @elseif ($model->comment)
    <div class="info-box">
        <p>Comment</p>
        <h5>{{ $model->comment }}</h5>
    </div>
    @endif
</div>

@if (!isset($signoffForm))
<h3 class="form-section-title">Add New</h3>
<div class="row">
    <div class="input-wrap col">
        <input type="hidden" class="js-ignore-status" value="1">
        @include('partials.product-picker', ['hideLineDrives' => true])
    </div>
</div>
@endif

@if ($errors->flash)
@include('partials.errors.error-flash', ['message' => $errors->flash->first()])
@endif
<div class="row">
    @include('inventoryremovals.line-item-table')
</div>
@include('modals.js-delete')

@if (isset($signoffForm) && Bouncer::can('signoff.inventory-removals.finance'))
<div class="row mb-4">
    <div class="col-12 d-flex justify-content-end">
        <a href="{{ route('exports.printinvremoval', $model->id) }}" class="btn ml-3">Export</a>
    </div>
</div>
@endif

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/modules/inventory-removals.js') }}"></script>
@endpush
