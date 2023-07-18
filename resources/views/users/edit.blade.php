@extends('layouts.app')

@section('page', 'Edit User')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header text-center">Manage User</h2>
                <div class="card-body">
                    @include('users.form')
                    @if (Bouncer::can('delete', App\User::class))
                    <button type="button" class="link-btn delete mt-3" data-toggle="modal" title="Delete user" data-action="{{ route('users.delete', $model->id) }}" data-label="{{ $model->name }}" data-target="#deleteModal">
                        <i class="material-icons">delete_forever</i>
                        Delete
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@if (Bouncer::can('delete', App\User::class))
@include('modals.delete')
@endif
@endsection
