@extends('layouts.app')

@section('page', 'Edit Inventory Removal Request')

@section('content')
@ray($model->signoff->responses)
<div class="container container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Edit Inventory Removal Request</h1>
            @include('inventoryremovals.form')
            @includeWhen($model->signoff && $model->signoff->rejected, 'partials.signoffs.responses')
        </div>
    </div>
</div>
@endsection
