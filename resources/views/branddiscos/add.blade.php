@extends('layouts.app')

@section('page', 'New Brand Disco Request')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12">
            <h1 class="text-center">New Brand Disco Request</h1>
            @include('branddiscos.form')
        </div>
    </div>
</div>
@endsection
