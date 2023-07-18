@extends('layouts.app')

@section('page', 'Edit Profile')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header text-center">Edit Profile</h2>
                <div class="card-body">
                    <livewire:profile.user-profile />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
