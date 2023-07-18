@extends('layouts.app')

@section('page', 'Currencies')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Currencies</h2>
            @can('edit', App\User::class)
            <a href="{{ route('currencies.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Currency
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
