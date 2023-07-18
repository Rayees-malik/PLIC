@extends('layouts.app')

@section('page', 'Users')

@section('content')
<div class="container container-xxl">
    @if (!auth()->user()->isVendor)
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            <div class="dropdown-wrap col-xl-4">
                <label>By Type</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="type">
                        <option value>All Users</option>
                        <option value="purity">Purity</option>
                        <option value="vendor">Vendor</option>
                        <option value="broker">Broker</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Users</h2>
            @can('edit', App\User::class)
            <a href="{{ route('users.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New User
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
<script type="text/javascript" src="{{ mix('js/datatable-filters.js') }}"></script>
{{ $datatable->scripts() }}
@endpush
