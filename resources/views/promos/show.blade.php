@extends('layouts.app')

@section('page', 'View Promo')

@section('content')
@include('partials.warning-notice')
<div class="container container-xxl view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Brand</p>
            <h2>{{ $model->brand->name }}</h2>
        </div>
        <div class="info-box">
            <p>Promo Period</p>
            <h3>{{ $model->period->name }}</h3>
        </div>
        <div class="info-box">
            <p>Start Date</p>
            <h3>{{ $model->period->start_date->toFormattedDateString() }}</h3>
        </div>
        <div class="info-box">
            <p>End Date</p>
            <h3>{{ $model->period->end_date->toFormattedDateString() }}</h3>
        </div>
        <div class="info-box">
            <p>Line Drive</p>
            <h3>{{ $model->line_drive ? 'Yes' : 'No' }}</h3>
        </div>
        @if ($model->period->owner)
        <div class="info-box">
            <p>{{ ucfirst($model->period->owner->getShortClassName()) }}</p>
            <h3>{{ $model->period->owner->name }}</h3>
        </div>
        @endif

        @if (Bouncer::can('edit', $model))
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>

            <div class="info-box-actions__dropdown">
                @if ($model->canUpdate && ($model->period->active || !auth()->user()->isVendor))
                <a class="primary-btn" href="{{ route('promos.edit', $model->id) }}">
                    Edit
                </a>
                @endif
                <a class="primary-btn" href="{{ route('promos.copy', $model->id) }}">
                    Copy
                </a>
                @if ($model->canUnsubmitApproved && $model->signoffs->count() && $model->{$model->stateField()} == App\Helpers\SignoffStateHelper::INITIAL)
                @if (auth()->user()->isVendor || Bouncer::can('signoff.product.promo.vendorrelations'))
                <button type="button" class="primary-btn" data-toggle="modal" title="Unsubmit Promo" data-action="{{ route('signoffs.unsubmit', $model->signoffs->first()) }}" data-label="{{ $model->name }}" data-target="#unsubmitModal">
                    Unsubmit
                </button>
                @endif
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header mt-2">
            @if (!$promoConfig || !Arr::get($promoConfig, 'onlyPercentDiscount'))
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Discount Type</p>
                        <h4>{{ $model->dollar_discount ? 'Dollar' : 'Percentage' }}</h4>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>MCB Only</p>
                        <h4>{{ $model->oi ? 'No' : 'Yes' }}</h4>
                    </div>
                </div>
                @if ($model->oi)
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>OI Dates</p>
                        <h4>{{ $model->oi_period_dates ? 'First to Last' : '15th to 15th' }}</h4>
                    </div>
                </div>
                @endif
            </div>
            @if ($promoConfig)
            <div class="row">
                @foreach (Arr::get($promoConfig, 'promoFields', []) as $field => $fieldConfig)
                {!! BladeHelper::promoHeaderField($model, $field, $fieldConfig, true) !!}
                @endforeach
            </div>
            @endif
        </div>
        <div class="mb-3"></div>

        <div class="row justify-content-end">
            <div class="input-wrap col-xl-4 ">
                <label>
                    Search
                    <div class="icon-input">
                        <i class="material-icons pre-icon">search</i>
                        <input type="text" class="js-promo-search" placeholder="">
                    </div>
                </label>
            </div>
        </div>
        <div class="js-promo-container">
            @include('promos.show-product-promo-table')
        </div>
    </div>
</div>
@include('modals.unsubmit')
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/promos-search.js') }}"></script>
@endpush
