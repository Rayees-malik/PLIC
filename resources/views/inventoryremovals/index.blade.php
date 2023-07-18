@extends('layouts.app')

@section('page', 'Inventory Removals')

@section('content')
<div class="container container-xxl">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            @if ($users->count())
            <div class="dropdown-wrap col-xl-4">
                <label>Submitted By</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="submitted_by">
                        <option value>All Users</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            @if ($brands->count())
            <div class="dropdown-wrap col-xl-4">
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
            <div class="dropdown-wrap col-xl-4">
                <label>Warehouse</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="warehouse">
                        <option value>All Warehouses</option>
                        <option value="01">01</option>
                        <option value="04">04</option>
                        <option value="08">08</option>
                        <option value="09">09</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="80">80</option>
                        <option value="90">90</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Inventory Removals</h2>
            @can('edit', App\Models\InventoryRemoval::class)
            <div>
                <a href="{{ route('inventoryremovals.create') }}" class="secondary-btn">
                    <i class="material-icons">add</i>
                    Add New Removal
                </a>
            </div>
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
