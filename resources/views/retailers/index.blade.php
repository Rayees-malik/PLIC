@extends('layouts.app')
@section('page', 'Retailers')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Retailers</h2>
            @can('create', App\Models\Retailer::class)
            <a href="{{ route('retailers.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add Retailer
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
