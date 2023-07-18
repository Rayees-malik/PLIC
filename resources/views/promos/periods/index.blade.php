@extends('layouts.app')

@section('page')
{{ $owner ? "{$owner->displayName} " : '' }}Promo Periods
@endsection

@section('content')
<div class="container">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            <div class="dropdown-wrap col-xl-3">
                <label>Type</label>
                <div class="dropdown-icon">
                    <select class="js-datatable-filter" data-filter="type">
                        <option value>All Types</option>
                        @foreach (App\Models\PromoPeriod::TYPES as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">
                @if ($owner)
                <a href="{{ route("{$owner->routePrefix}.show", $owner->id) }}" class="text-link">{{ $owner->displayName }}</a> Promo Periods
                @else
                Promo Periods
                @endif
            </h2>
            <div class="d-flex">
                <form method="POST" action="{{ $owner ? route("{$owner->routePrefix}.promos.periods.generate", $owner->id) : route('promos.periods.generate') }}" class="mr-2">
                    @csrf
                    <button class="secondary-btn" type="submit">
                        <i class="material-icons">build</i>
                        Generate Periods
                    </button>
                </form>
                <a href="{{ $owner ? route("{$owner->routePrefix}.promos.periods.create", $owner->id) : route('promos.periods.create') }}" class="secondary-btn">
                    <i class="material-icons">add</i>
                    New Period
                </a>
            </div>
        </div>
        <div class="tabs-wrap">
            <div class="tabs-header">
                <a class="tab-btn {{ $selected == 'active' ? 'tab-selected' : '' }}" href="{{ $owner ? route("{$owner->routePrefix}.promos.periods.index", $owner->id) : route('promos.periods.index') }}">
                    Active
                </a>
                <a class="tab-btn {{ $selected == 'inactive' ? 'tab-selected' : '' }}" href="{{ $owner ? route("{$owner->routePrefix}.promos.periods.inactive", $owner->id) : route('promos.periods.inactive') }}">
                    Inactive
                </a>
            </div>
            <div class="tabs-body">
                <div class="table-responsive-xl">
                    {{ $datatable->table() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/datatable-filters.js') }}"></script>
<script type="text/javascript" src="{{ mix('js/modules/promo-periods.js') }}"></script>
{{ $datatable->scripts() }}
@endpush
