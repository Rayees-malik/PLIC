@extends('layouts.app')

@section('page', 'Edit Unit of Measurement')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    <h2 class="mb-1">Manage Units of Measurement</h2>
                    <p class="mb-0 lead">{{ $model->description }}</p>
                </div>

                <div class="card-body">
                    @include('uom.form')

                    <button type="button" class="link-btn mt-3 delete" data-toggle="modal" title="Delete product unit" data-action="{{ route('uom.delete', $model->id) }}" data-label="{{ $model->description }}" data-target="#deleteModal">
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
