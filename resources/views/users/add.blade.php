@extends('layouts.app')

@section('page', 'Add User')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8 text-center">
            <div class="card">
                <h2 class="card-header text-center">Add User</h2>
                <div class="card-body">
                    @include('users.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

