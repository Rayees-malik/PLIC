@extends('layouts.app')

@section('page', 'Countries')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Countries</h2>
            @can('edit', App\User::class)
            <a href="{{ route('countries.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Country
            </a>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive-xl">
                {{ $datatable->table() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $datatable->scripts() }}
@endpush
