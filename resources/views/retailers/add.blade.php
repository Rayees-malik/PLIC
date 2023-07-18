@extends('layouts.app')

@section('page', 'Add Retailer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center">Add Retailer</h1>
            @include('retailers.form')
        </div>
    </div>
</div>
@endsection
