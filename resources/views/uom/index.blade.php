@extends('layouts.app')

@section('page', 'Units of Measurement')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Units of Measurement</h2>
            @can('lookups.edit')
            <a href="{{ route('uom.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Unit of Measurement
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
