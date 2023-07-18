@extends('layouts.app')

@section('page', 'Edit Ability')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card">
                <h2 class="card-header">Manage Abilities</h2>
                <div class="card-body">
                    @include('abilities.form')
                    @can('delete', \Silber\Bouncer\Database\Ability::class)
                        <button type="button" class="link-btn mt-3 delete" data-toggle="modal" title="Delete ability" data-action="{{ route('abilities.delete', $model->name) }}" data-label="{{ $model->title }}" data-target="#deleteModal">
                            <i class="material-icons">delete_forever</i>
                            Delete
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@can('delete', \Silber\Bouncer\Database\Ability::class))
    @include('modals.delete')
@endcan
@endsection
