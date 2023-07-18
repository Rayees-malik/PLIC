@extends('layouts.app')

@section('page', 'View Inventory Removal Request')

@section('content')
@include('partials.warning-notice')
<div class="container container-xxl view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Id</p>
            <h2>#{{ $model->cloned_from_id ?? $model->id }}</h2>
        </div>

        <div class="info-box">
            <p>Submitted By</p>
            <h2>{{ $model->user->name }}</h2>
        </div>

        @can('signoff.inventory-removals.finance')
        <div class="info-box info-box-pull-right">
            <a class="primary-btn" href="{{ route('exports.printinvremoval', $model->id) }}">
                Export
            </a>
        </div>
        @endcan

        @if (Bouncer::can('edit', $model) && $model->canUpdate && $model->{$model->stateField()} != App\Helpers\SignoffStateHelper::INITIAL)
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>
            <div class="info-box-actions__dropdown">
                <a class="primary-btn" href="{{ route('inventoryremovals.edit', $model->id) }}">
                    Edit
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header mt-2">
            @if ($model->comment)
            <div class="row">
                <div class="col-xl-12">
                    <div class="info-box">
                        <p>Comment</p>
                        <h5>{{ $model->comment }}</h5>
                    </div>
                </div>
            </div>
            @endif
        </div>
        @include('inventoryremovals.show-line-item-table')
    </div>

    @include('partials.signoffs.view-history')
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
