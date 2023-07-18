@extends('layouts.app')

@section('page', 'Add PAF')

@section('content')
<div class="container container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Add Pricing Adjustment Form</h1>
            @include('pricingadjustments.form')
        </div>
    </div>
</div>
@endsection
