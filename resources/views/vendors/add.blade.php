@extends('layouts.app')

@section('page', 'Add Vendor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center">Add Vendor</h1>
            @include('vendors.form')
        </div>
    </div>
</div>
@endsection
