@extends('layouts.app')

@section('page', 'Brand Disco Requests')

@section('content')
<div class="container container-xxl">
    @if (count($users) > 1)
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            <div class="dropdown-wrap col-xl-4">
                <label>By User</label>
                <div class="dropdown-icon">
                    <select class="searchable js-datatable-filter" data-filter="submitted_by">
                        <option value>All Users</option>
                        @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Brand Disco Requests</h2>
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
