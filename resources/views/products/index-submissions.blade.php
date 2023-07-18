@extends('layouts.app')
@section('page', 'Pending Product Submissions')

@section('content')
<div class="container container-xxl">
    <div class="datatable-filters-wrap">
        <h4>Filters</h4>
        <hr>
        <div class="row">
            <div class="dropdown-wrap col-xl-4">
                <label>By Type</label>
                <div class="dropdown-icon">
                    <select class="searchable js-submission-type">
                        <option value="pending">Pending</option>
                        <option value="rejected" {{ $type == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="drafts" {{ $type == 'drafts' ? 'selected' : '' }}>Drafts</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Product Submissions</h2>
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
{{ $datatable->scripts() }}

<script type="text/javascript">
    $('.js-submission-type').on('change', function () {
        window.location.href = `/products/submissions/${$(this).val()}`;
    });
</script>
@endpush
