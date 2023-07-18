@extends('layouts.app')

@section('page', 'Brands')

@section('content')
<div class="container container-xxl">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            @if ($vendors->count() > 1)
            <div class="dropdown-wrap col-xl-4">
                <label>Vendor</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="vendor_id">
                        <option value>All Vendors</option>
                        @foreach ($vendors as $vendor)
                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
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
            <h2 class="mb-0">Brands</h2>
            @can('create', App\Models\Brand::class)
            <a href="{{ route('brands.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Brand
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
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/datatable-filters.js') }}"></script>
{{ $datatable->scripts() }}
@endpush
