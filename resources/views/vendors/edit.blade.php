@extends('layouts.app')

@section('page', 'Edit Vendor')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center">Manage Vendor</h1>
            @include('vendors.form')
            @includeWhen($model->signoff && $model->signoff->rejected, 'partials.signoffs.responses')
        </div>
    </div>
</div>
@include('modals.delete')
@endsection
