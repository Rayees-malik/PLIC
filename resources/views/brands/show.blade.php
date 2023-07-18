@extends('layouts.app')

@section('page', 'View Brand')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Brand</p>
            <h2>
                {{ $model->name }}
                @if ($model->made_in_canada)
                <img src="{{ asset('/images/maple-leaf.png') }}" alt="Made in Canada" style="width: 20px; margin-bottom: 5px;">
                @endif
            </h2>
        </div>

        <div class="info-box">
            <p>Vendor</p>
            <a href="{{ route('vendors.show', $model->vendor->id) }}">
                <h3>{{ $model->vendor->name }}</h3>
            </a>
        </div>
        <div class="info-box">
            <p>Phone number</p>
            <h3>{{ $model->phone }}</h3>
        </div>
        <div class="info-box">
            <p>Website</p>
            <h3>{{ $model->website }}</h3>
        </div>
        <div class="info-box">
            <p>Status</p>
            <h3>{{ App\Helpers\StatusHelper::toString($model->status) }}</h3>
        </div>

        <div class="info-box info-box-pull-right">
            <p>Brand Number</p>
            <h3>{{ $model->brand_number }}</h3>
        </div>

        @if (Bouncer::can('edit', $model))
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>

            <div class="info-box-actions__dropdown">
                @if ($model->canUpdate)
                <a class="primary-btn" href="{{ route('brands.edit', $model->id) }}">
                    Edit
                </a>
                @endif
                <a class="primary-btn" href="{{ route('brands.copy', $model->id) }}">
                    Copy
                </a>
                <a class="primary-btn" href="{{ route('brands.categories', $model->id) }}">
                    Catalogue Categories
                </a>
                @if ($model->status !== App\Helpers\StatusHelper::DISCONTINUED && ($model->vendor_relations_specialist_id == auth()->id() || auth()->user()->can('admin')))
                <a class="primary-btn" href="{{ route('branddiscos.create', $model->id) }}">
                    Disco Request
                </a>
                @endif
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header">
            <div class="row">
                <div class="col-xl-4">
                    <figure class="view-figure">
                        <div style="height: 100%;" class="d-flex align-items-center justify-content-center">
                            {!! BladeHelper::showFirstImage($model, 'logo', 'thumb') !!}
                        </div>
                    </figure>
                </div>
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-4">
                            <div class="info-box">
                                <p>Currency</p>
                                <h4>{{ $model->currency->name }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-box">
                                <p>Brokers</p>
                                <h4>{{ implode(', ', $model->brokers->pluck('name')->toArray()) }}</h4>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="info-box">
                                <p>Export Product Data</p>
                                <form method="POST" action="{{ route('exports.export', 'productdata') }}">
                                    @csrf
                                    <input type="hidden" name="brand_id[]" value="{{ $model->id }}">
                                    <button type="submit" class="primary-btn block-btn" title="Export">
                                        <i class="material-icons">save_alt</i>
                                        Export
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="info-box">
                                <p>Description</p>
                                <h4>{{ $model->description }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabs-wrap mt-5">
            <div class="tabs-header">
                <div class="tab-btn tab-selected" name="distribution">Distribution</div>
                <div class="tab-btn" name="purchasing">Purchasing</div>
                <div class="tab-btn" name="media">Media</div>
                <div class="tab-btn" name="contacts">Contacts</div>
                <div class="tab-btn" name="french">French</div>
                <div class="tab-btn" name="administrative">Administrative</div>
                @if ($model->{$model->stateField()} === \App\Helpers\SignoffStateHelper::INITIAL && ($model->signoffs->count() || $model->discoRequests->count()))
                <div class="tab-btn" name="history">History</div>
                @endif
            </div>

            <div class="tabs-body">
                <div class="distribution-tab">
                    @include("brands.view-tabs.distribution")
                </div>
                <div class="purchasing-tab" style="display:none">
                    @include("brands.view-tabs.purchasing")
                </div>
                <div class="media-tab" style="display:none">
                    @include("brands.view-tabs.media")
                </div>
                <div class="contacts-tab" style="display:none">
                    @include("brands.view-tabs.contacts")
                </div>
                <div class="french-tab" style="display:none">
                    @include("brands.view-tabs.french")
                </div>
                <div class="administrative-tab" style="display:none">
                    @include("brands.view-tabs.administrative")
                </div>
                @if ($model->{$model->stateField()} === \App\Helpers\SignoffStateHelper::INITIAL && ($model->signoffs->count() || $model->discoRequests->count()))
                <div class="history-tab" style="display:none;">
                    @include("brands.view-tabs.history")
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
