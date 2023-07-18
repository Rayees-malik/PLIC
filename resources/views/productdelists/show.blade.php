@extends('layouts.app')

@section('page', 'View Product Delist Request')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Product</p>
            <h2>{{ $model->product->getName() }} (#{{ $model->product->stock_id }})</h2>
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
                        <p>Delist Reason</p>
                        <h3>{{ $model->reason }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.signoffs.view-history')
</div>
@endsection
