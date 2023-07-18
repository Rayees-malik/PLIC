@extends('layouts.app')

@section('page', 'Edit MAF')

@section('content')
<div class="container container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Edit Marketing Agreement Form</h1>
            @include('marketingagreements.form')
            @includeWhen($model->signoff && $model->signoff->rejected, 'partials.signoffs.responses')
        </div>
    </div>
</div>
@endsection
