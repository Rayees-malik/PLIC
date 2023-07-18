@extends('layouts.app')

@section('page', 'Edit Broker')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
                <div class="card-header">
                    <h2 class="mb-1">{{ $model->id ? 'Edit' : 'Add' }} Broker</h2>
                    @if ($model->id)<p class="mb-0 lead">{{ $model->name }}</p>@endif
                </div>

                <div class="card-body">
                    @include('brokers.form')

                    @if ($model->id)
                    <button type="button" class="link-btn mt-3 delete" data-toggle="modal" title="Delete broker" data-action="{{ route('brokers.delete', $model->id) }}" data-label="{{ $model->name }}" data-target="#deleteModal">
                        <i class="material-icons">delete_forever</i>
                        Delete
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@includeWhen($model->id, 'modals.delete')
@endsection
