@extends('layouts.app')

@section('page', 'Edit Country')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    <h2 class="mb-1">Manage Countries</h2>
                    <p class="lead mb-0">{{ $model->name }}</p>
                </div>

                <div class="card-body">
                    @include('countries.form')
                    <button type="button" class="link-btn mt-3 delete" data-toggle="modal" title="Delete country" data-action="{{ route('countries.delete', $model->id) }}" data-label="{{ $model->name }}" data-target="#deleteModal">
                        <i class="material-icons">delete_forever</i>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.delete')
@endsection
