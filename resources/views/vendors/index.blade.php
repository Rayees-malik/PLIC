@extends('layouts.app')
@section('page', 'Vendors')

@section('content')
<div class="container container-xxl">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            <div class="dropdown-wrap col-xl-4">
                <label>Status</label>
                <div class="dropdown-icon">
                    <select class="js-datatable-filter" data-filter="status">
                        <option value="20">Active</option>
                        <option value="30">Disco</option>
                        <option value>All Statuses</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Vendors</h2>
            @if(Bouncer::can('create', App\Models\Vendor::class) && !auth()->user()->vendor_id)
            <a href="{{ route('vendors.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Vendor
            </a>
            @endif
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
<script type="text/javascript" src="{{ mix('js/datatable-filters.js') }}"></script>
{{ $datatable->scripts() }}
@endpush
