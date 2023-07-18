@extends('layouts.app')

@section('page', 'View Retailer')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <h2>{{ $model->name }}</h2>
        </div>

        @if ($model->allow_promos)
        <div class="info-box info-box-pull-right">
            <a href="{{ route('retailers.promos.periods.index', $model->id) }}" class="btn mr-3">Promo Periods</a>
            <a href="{{ route('retailers.promos.index', $model->id) }}" class="btn">Promos</a>
        </div>
        @endif

        @if (Bouncer::can('edit', $model))
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>

            <div class="info-box-actions__dropdown">
                @if ($model->canUpdate)
                <a class="primary-btn" href="{{ route('retailers.edit', $model->id) }}">
                    Edit
                </a>
                @endif
                <a class="primary-btn" href="{{ route('retailers.imports', $model->id) }}">
                    Imports
                </a>
                <a class="primary-btn" href="{{ route('retailers.exports', $model->id) }}">
                    Exports
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header">
            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <p>Distributors</p>
                        <h4>{{ $model->distributors->count() ? implode(', ', $model->distributors->pluck('name')->toArray()) : '-' }}</h4>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="info-box">
                        <p># Stores</p>
                        <h4>{{ $model->number_stores ?? '-' }}</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info-box">
                        <p>Start of Fiscal Year</p>
                        <h4>{{ $model->fiscal_year_start ?? '-' }}</h4>
                    </div>
                </div>
                @if ($model->financeManager)
                <div class="col-4">
                    <div class="info-box">
                        <p>Key Account Manager</p>
                        <h4>
                            <a href="{{ route('users.show', $model->financeManager->id) }}">{{ $model->financeManager->name }}</a>
                        </h4>
                    </div>
                </div>
                @endif
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="info-box">
                        <p>Markup</p>
                        <h4>{{ $model->markup ?? '-' }}</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info-box">
                        <p>Target Margin</p>
                        <h4>{{ $model->target_margin ?? '-' }}</h4>
                    </div>
                </div>
                <div class="col-4">
                    <div class="info-box">
                        <p>AS400 Pricing File</p>
                        <h4>{{ $model->as400_pricing_file ?? '-' }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="tabs-wrap mt-5">
            <div class="tabs-header">
                <div class="tab-btn tab-selected" name="general">General</div>
                <div class="tab-btn" name="contacts">Contacts</div>
            </div>

            <div class="tabs-body">
                <div class="general-tab">
                    @include("retailers.view-tabs.general")
                </div>
                <div class="contacts-tab" style="display:none">
                    @include("retailers.view-tabs.contacts")
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
