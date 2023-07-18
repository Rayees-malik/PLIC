@extends('layouts.app')

@section('page', 'Add Product')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center">Add Product</h1>
            @include('products.form')
        </div>
    </div>
</div>
@endsection
