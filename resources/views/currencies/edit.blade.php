@extends('layouts.app')

@section('page', 'Edit Currency')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    <h2 class="mb-1">Edit Currency</h2>
                    <p class="mb-0 lead">{{ $model->name }}</p>
                </div>
                <div class="card-body">
                    @include('currencies.form')

                    <button type="button" class="link-btn mt-3 delete" data-toggle="modal" title="Delete currency" data-action="{{ route('currencies.delete', $model->id) }}" data-label="{{ $model->name }}" data-target="#deleteModal">
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
