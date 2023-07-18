@extends('layouts.app')

@section('page', 'Product Delist Requests')

@section('content')
<div class="container container-xxl">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Product Delist Requests</h2>
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
