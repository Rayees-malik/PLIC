@extends('layouts.app')
@section('page', 'Products')

@section('content')
<div class="container container-xxl">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            @if ($brands->count() > 1)
            <div class="dropdown-wrap col-xl-3">
                <label>Brand</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="brand_id">
                        <option value>All Brands</option>
                        @foreach ($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="dropdown-wrap col-xl-3">
                <label>Status</label>
                <div class="dropdown-icon">
                    <select class="js-datatable-filter" data-filter="status">
                        <option value="A">Active</option>
                        <option value="S">Superseded</option>
                        <option value="D">Disco</option>
                        <option value>All Statuses</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Products</h2>
            @can('edit', App\Models\Product::class)
            <a href="{{ route('products.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Product
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
