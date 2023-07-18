@extends('layouts.app')

@section('page', 'Edit Role')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    <h2 class="mb-1">Manage Role Abilities</h2>
                    <p class="lead mb-0">{{ $model->title }}</p>
                </div>
                <div class="card-body">
                    @include('roles.form')
                    @can('delete', \Silber\Bouncer\Database\Ability::class)
                    <button type="button" class="link-btn mt-3 delete" data-toggle="modal" title="Delete role" data-action="{{ route('roles.delete', $model->name) }}" data-label="{{ $model->title }}" data-target="#deleteModal">
                        <i class="material-icons">delete_forever</i>
                        Delete
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@can('delete', \Silber\Bouncer\Database\Role::class))
@include('modals.delete')
@endcan
@endsection
