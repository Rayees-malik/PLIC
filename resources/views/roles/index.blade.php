@extends('layouts.app')

@section('page', 'Roles')

@section('content')
<div class="container">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            @if ($abilities->count() > 1)
            <div class="dropdown-wrap col-xl-3">
                <label>Ability</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="ability_id">
                        <option value>All Abilities</option>
                        @foreach ($abilities as $ability)
                        <option value="{{ $ability->id }}">{{ $ability->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Roles</h2>
            @can('user.roles.edit')
            <a href="{{ route('roles.create') }}" class="secondary-btn">
                <i class="material-icons">add</i>
                Add New Role
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
