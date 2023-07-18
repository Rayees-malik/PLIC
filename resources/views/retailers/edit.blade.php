@extends('layouts.app')

@section('page', 'Manage Retailer')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center">Manage Retailer</h1>
            @include('retailers.form')
            @includeWhen($model->signoff && $model->signoff->rejected, 'partials.signoffs.responses')
        </div>
    </div>
</div>
@endsection
