@extends('layouts.app')

@section('page', 'View Brand Disco Request')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Brand</p>
            <h2>{{ $model->brand->name }}</h2>
        </div>
        <div class="info-box info-box-pull-right">
            <p>Submitted by</p>
            <h2>{{ $model->user->name }}</h2>
        </div>
    </div>

    <div class="view-page-body">
        <div class="view-page-header">
            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <p>Disco Reason</p>
                        <h3>{{ $model->reason }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <p>Plan to Recoup $</p>
                        <h3>{{ $model->recoup_plan }}</h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>A/P Owed</p>
                        <h2>${{ $model->ap_owed }}</h2>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Inventory Value</p>
                        <h2>${{ $model->inventory_value }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>YTD Sales</p>
                        <h2>${{ $model->ytd_sales }}</h2>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>YTD Margin</p>
                        <h2>{{ $model->ytd_margin }}%</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Previous Year Sales</p>
                        <h2>${{ $model->previous_year_sales }}</h2>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Previous Year Margin</p>
                        <h2>{{ $model->previous_year_margin }}%</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.signoffs.view-history')
</div>
@endsection
