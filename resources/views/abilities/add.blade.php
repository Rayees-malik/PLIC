@extends('layouts.app')

@section('page', 'Add Ability')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header">Add Ability</h2>
                <div class="card-body">
                    @include('abilities.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
