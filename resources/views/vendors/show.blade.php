@extends('layouts.app')

@section('page', 'View Vendor')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Vendor</p>
            <h2>{{ $model->name }}</h2>
        </div>
        <div class="info-box">
            <p>Phone number</p>
            <h3>{{ $model->phone }}</h3>
        </div>

        <div class="sticky-address-box">
            {!! optional($model->address)->longFormat() !!}
        </div>

        @if(Bouncer::can('edit', $model) && $model->canUpdate)
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>

            <div class="info-box-actions__dropdown">
                <a class="primary-btn" href="{{ route('vendors.edit', $model->id) }}">
                    Edit
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header">
            <h3 class="form-section-title">Brands</h3>

            <div class="row">
                @foreach($model->brands as $brand)
                <div class="col-s-6 col-xl-3">
                    <figure class="view-figure">
                        <a href="{{ route('brands.show', $brand->id) }}">
                            <div style="height: 128px;" class="d-flex align-items-center justify-content-center">
                                {!! BladeHelper::showFirstImage($brand, 'logo', 'small_thumb') !!}
                            </div>
                        </a>
                        <figcaption><a href="{{ route('brands.show', $brand->id) }}">{{ $brand->name }}</a></figcaption>
                    </figure>
                </div>
                @endforeach
            </div>
        </div>

        <div class="tabs-wrap mt-5">
            <div class="tabs-header">
                <div class="tab-btn tab-selected" name="payment">Payment & Ordering</div>
                <div class="tab-btn" name="contacts">Contacts</div>
                @if($model->{$model->stateField()} === \App\Helpers\SignoffStateHelper::INITIAL && $model->signoffs->count())
                <div class="tab-btn" name="history">History</div>
                @endif
            </div>
            <div class="tabs-body">
                <div class="payment-tab">
                    @include("vendors.view-tabs.payment")
                </div>
                <div class="contacts-tab" style="display:none;">
                    @include("vendors.view-tabs.contacts")
                </div>
                @if($model->{$model->stateField()} === \App\Helpers\SignoffStateHelper::INITIAL && $model->signoffs->count())
                <div class="history-tab" style="display:none;">
                    @include("vendors.view-tabs.history")
                </div>
                @endif
            </div>
        </div>
    </div>

    @includeWhen(!auth()->user()->isVendor && $model->{$model->stateField()} !== \App\Helpers\SignoffStateHelper::INITIAL, 'partials.signoffs.view-history')
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
