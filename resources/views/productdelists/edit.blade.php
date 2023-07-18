@extends('layouts.app')

@section('page', 'Edit Product Delist Request')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Edit Product Delist Request</h1>
            @include('productdelists.form')
            @includeWhen($model->signoff && $model->signoff->rejected, 'partials.signoffs.responses')
        </div>
    </div>
</div>
@endsection
