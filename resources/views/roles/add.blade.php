@extends('layouts.app')

@section('page', 'Add Role')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header text-center">Add Role</h2>

                <div class="card-body">
                    @include('roles.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

