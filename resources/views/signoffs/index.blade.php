@extends('layouts.app')

@section('page', 'Signoffs')

@section('content')
<div class="container container-xxl">
    @if (count($signoffTypes) > 1)
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            <div class="dropdown-wrap col-xl-4">
                <label>By Type</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="signoff_type">
                        <option value>All Types</option>
                        @foreach ($signoffTypes as $type => $display)
                        <option value="{{ $type }}" {{ session('signoff_filter') == $type ? ' selected' : '' }}>{{ $display }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Signoffs</h2>

            <div class="d-flex">
            @can('signoff.product.finance')
            <a href="{{ route('signoffs.finance.review') }}" class="secondary-btn mx-2">
                <i class="material-icons">done_all</i>
                Finance Bulk Approvals
            </a>
            @endcan

            @can('signoff.product.management')
            <a href="{{ route('signoffs.management') }}" class="secondary-btn mx-2">
                <i class="material-icons">done_all</i>
                Management Bulk Approvals
            </a>
            @endcan

            @can('signoff.webseries')
            <a href="{{ route('signoffs.webseries.review') }}" class="secondary-btn mx-2">
                <i class="material-icons">done_all</i>
                Webseries Bulk Approvals
            </a>
            @endcan
            </div>
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
