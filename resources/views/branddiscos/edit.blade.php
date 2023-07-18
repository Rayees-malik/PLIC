@extends('layouts.app')

@section('page', 'Edit Brand Disco Request')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Edit Brand Disco Request</h1>
            @include('branddiscos.form')
            @includeWhen($model->signoff && $model->signoff->rejected, 'partials.signoffs.responses')
        </div>
    </div>
</div>
@endsection
