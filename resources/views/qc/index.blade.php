@extends('layouts.app')

@section('page', 'Quality Control')

@section('content')
<div class="container container-xxl">
  <div class="datatable-filters-wrap">
    <h4>Filters</h4>
    <hr>
    <div class="row">
        <div class="dropdown-wrap col-xl-3">
            <label>Warehouse</label>
            <div class="dropdown-icon">
                <select class="searchable js-datatable-filter" data-filter="warehouse_id">
                    <option value>All Warehouses</option>
                    @foreach ($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}">{{ $warehouse->number . ' - ' .$warehouse->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h2 class="mb-0">Quality Control Records</h2>

      @canany(['update', 'create',], App\Models\QualityControlRecord::class)
      <a href="{{ route('qc.create') }}" class="secondary-btn">
        <i class="material-icons">add</i>
        Create New
      </a>
      @endcanany
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
