@extends('layouts.app')

@section('page', 'Add MAF')

@section('content')
<div class="container container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">Add Marketing Agreement Form</h1>
            @include('marketingagreements.form')
        </div>
    </div>
</div>
@endsection
