@extends('layouts.app')

@section('page', 'New Inventory Removal Request')

@section('content')
<div class="container container-xxl">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">New Inventory Removal Request</h1>
            @include('inventoryremovals.form')
        </div>
    </div>
</div>
@endsection
