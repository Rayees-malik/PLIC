@extends('layouts.app')

@section('page', isset($tableHeader) ? $tableHeader : 'Promos')

@section('content')
<div class="container container-xxl">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            @if ($retailers->count() > 1)
            <div class="dropdown-wrap col-xl-4">
                <label>Retailer</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="retailer_id">
                        <option value>All Retailers</option>
                        @foreach ($retailers as $retailer)
                        <option value="{{ $retailer->id }}">{{ $retailer->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            @if ($brands->count() > 1)
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
            @if ($periods->count() > 1)
            <div class="dropdown-wrap col-xl-4">
                <label>Promo Period</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="period_id">
                        <option value>All Periods</option>
                        @foreach ($periods as $period)
                        <option value="{{ $period->id }}">{{ $period->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
            <div class="input-wrap col-xl-4">
                <label>By Date</label>
                <div class="icon-input">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input type="text" class="js-datepicker js-datatable-filter" data-filter="for_date">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">
                @if (isset($tableHeader))
                {{ $tableHeader }}
                @elseif ($owner)
                <a href="{{ route("{$owner->routePrefix}.show", $owner->id) }}" class="text-link">{{ $owner->displayName }}</a> Promos
                @else
                Promos
                @endif
            </h2>
            @can('edit', App\Models\Promo::class)
            <div class="form-check-inline">
                @if ($retailers->count() > 1)
                <div class="dropdown-wrap mt-3 mr-3">
                    <select class="searchable form-control js-promo-retailer">
                        <option value>Select Retailer</option>
                        @foreach ($retailers as $retailer)
                        <option value="{{ $retailer->id }}">{{ $retailer->name }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                @if (!$owner && Bouncer::can('promos.disco'))
                <a href="{{ route('discopromos.edit') }}" class="secondary-btn mr-5">
                    <i class="material-icons">price_change</i>
                    Disco Promos
                </a>
                @endif
                @if (count($retailers))
                @can('edit', App\Models\PromoPeriod::class)
                <button type="button" class="secondary-btn mr-3 js-periods-button">
                    <i class="material-icons">calendar_today</i>
                    Promo Periods
                </button>
                @endcan
                <button type="button" class="secondary-btn mr-3 js-new-button">
                    <i class="material-icons">calendar_today</i>
                    New Promo
                </button>
                @else
                @can('edit', App\Models\PromoPeriod::class)
                <a href="{{ count($retailers) ? 'javascript:void(0);' : ($owner ? route("{$owner->routePrefix}.promos.periods.index", $owner->id) : route('promos.periods.index')) }}" class="secondary-btn mr-3 js-periods-button">
                    <i class="material-icons">calendar_today</i>
                    Promo Periods
                </a>
                @endcan
                <a href="{{ count($retailers) ? 'javascript:void(0);' : ($owner ? route("{$owner->routePrefix}.promos.create", $owner->id) : route('promos.create')) }}" class="secondary-btn js-new-button">
                    <i class="material-icons">add</i>
                    New Promo
                </a>
                @endif
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
@if (count($retailers))
<script type="text/javascript">
    $(function () {
        $('.js-periods-button').on('click', function () {
            const retailer = $('.js-promo-retailer').val();
            if (!retailer) {
                return document.getElementById('notification').dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        content: 'You must select a retailer first.',
                        type: 'error'
                    },
                    bubbles: true,
                    cancelable: true
                }));
            }

            window.location.href = '{{ route("{$owner->routePrefix}.promos.periods.index", "OWNER_ID") }}'.replace('OWNER_ID', retailer);
        });

        $('.js-new-button').on('click', function () {
            const retailer = $('.js-promo-retailer').val();
            if (!retailer) {
                return document.getElementById('notification').dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        content: 'You must select a retailer first.',
                        type: 'error'
                    },
                    bubbles: true,
                    cancelable: true
                }));
            }

            window.location.href = '{{ route("{$owner->routePrefix}.promos.create", "OWNER_ID") }}'.replace('OWNER_ID', retailer);
        });
    });

</script>
@endif
{!! BladeHelper::initChosenSelect('searchable') !!}
<script type="text/javascript" src="{{ mix('js/datatable-filters.js') }}"></script>
{{ $datatable->scripts() }}
@endpush
