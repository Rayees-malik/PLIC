@extends('layouts.app')

@section('page', 'Pricing Adjustment Forms')

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
            @if ($accounts->count())
            <div class="dropdown-wrap col-xl-4">
                <label>Account</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="account">
                        <option value>All Accounts</option>
                        @foreach ($accounts as $account)
                        <option value="{{ $account->customer_number }}|{{ $account->price_code }}">{{ $account->name }} ({{ $account->customer_number }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Pricing Adjustment Forms</h2>
            @can('edit', App\Models\PricingAdjustment::class)
            <div>
                <a href="{{ route('pricingadjustments.create') }}" class="secondary-btn">
                    <i class="material-icons">add</i>
                    Add New PAF
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
