@extends('layouts.app')

@section('page', 'View Marketing Agreement')

@section('content')
@include('partials.warning-notice')
<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <p>Id</p>
            <h2>#{{ $model->cloned_from_id ?? $model->id }}</h2>
        </div>

        <div class="info-box">
            <p>Submitted By</p>
            <h2>{{ $model->user->name }}</h2>
        </div>

        <div class="info-box">
            <p>Account</p>
            <h2>{{ $account }}</h2>
        </div>

        @if (Bouncer::can('edit', $model) && $model->canUpdate && $model->{$model->stateField()} != App\Helpers\SignoffStateHelper::INITIAL)
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>
            <div class="info-box-actions__dropdown">
                <a class="primary-btn" href="{{ route('marketingagreements.edit', $model->id) }}">
                    Edit
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header mt-2">
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Ship To #</p>
                        <h2>{{ $model->ship_to_number }}</h2>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Retailer Invoice #</p>
                        <h2>{{ $model->retailer_invoice }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="info-box">
                        <p>Comment</p>
                        <h5>{{ $model->comment }}</h5>
                    </div>
                </div>
            </div>
            @if ($model->media->count())
            <div class="row ml-3 mb-4">
                <div class="col">
                    <div class="info-box">
                        <p>File Uploads</p>
                        @foreach ($model->media as $media)
                        {!! $media->getDownloadLink() !!}<br>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
        @include('marketingagreements.show-line-item-table')

        @if (Bouncer::can('signoff.maf.accounting'))
        <div class="row mb-4">
            <div class="col">
                <a href="{{ route('exports.mafjournal', $model->id) }}" class="btn ml-3">Export Marketing Agreement Journal</a><br>
                @foreach ($lineItemBrands as $brandId => $brandName)
                <a href="{{ route('exports.mafchargeback', ['id' => $model->id, 'brandId' => $brandId]) }}" class="btn ml-3 mt-3">Export MCB Charge Back for {{ $brandName }}</a>
                @endforeach
            </div>
        </div>
        @endif

        <div class="row">
            <div class="col-xl-12">
                <div class="info-box">
                    <p>Approval Email</p>
                    <small>{{ $model->approval_email }}</small>
                </div>
            </div>
        </div>
    </div>

    @include('partials.signoffs.view-history')
</div>
@endsection
