@extends('layouts.app')

@section('page', 'Change Password')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header text-center">Change Password</h2>
                <div class="card-body">
                    <livewire:profile.change-password />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
