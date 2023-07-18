@extends('layouts.app')

@section('page', 'New Product Delist Request')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">New Product Delist Request</h1>
            @include('productdelists.form')
        </div>
    </div>
</div>
@endsection
