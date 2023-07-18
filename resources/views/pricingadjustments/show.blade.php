@extends('layouts.app')

@section('page', 'View Pricing Adjustment')

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

        @if (Bouncer::can('edit', $model) && $model->canUpdate && $model->{$model->stateField()} != App\Helpers\SignoffStateHelper::INITIAL)
        <div class="info-box-actions info-box-pull-right">
            <div class="info-box-actions__btn">
                <span>EDIT</span>
                <span class="material-icons">expand_more</span>
            </div>
            <div class="info-box-actions__dropdown">
                <a class="primary-btn" href="{{ route('pricingadjustments.edit', $model->id) }}">
                    Edit
                </a>
            </div>
        </div>
        @endif
    </div>

    <div class="view-page-body">
        <div class="view-page-header mt-2">
            <div class="row">
                <div class="col-xl-12">
                    <div class="info-box">
                        <p>{{ Str::plural('Account', count($model->accounts)) }}</p>
                        <h2>{{ implode(', ', $accounts) }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>On-going</p>
                        <h2>{{ $model->ongoing ? 'Yes' : 'No' }}</h2>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Dates</p>
                        <h2>{{ $model->start_date->toFormattedDateString() }} - {{ $model->end_date->toFormattedDateString() }}</h2>
                    </div>
                </div>
              </div>
            <div class="row">
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>Discount Type</p>
                        <h2>{{ $model->dollar_discount ? 'Fixed Price' : 'Percent' }}</h2>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="info-box">
                        <p>MCB Type</p>
                        <h2>{{ $model->dollar_mcb ? 'Dollar' : 'Percent' }}</h2>
                    </div>
                </div>
            </div>
            @if ($model->notes && $model->submitted_by = auth()->id())
            <div class="row">
                <div class="col-xl-12">
                    <div class="info-box">
                        <p>Private Notes</p>
                        <h5>{{ $model->notes }}</h5>
                    </div>
                </div>
            </div>
            @endif
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
        @include('pricingadjustments.show-line-item-table')

        @if (Bouncer::can('signoff.paf.pricing'))
        <div class="row mb-3">
            <div class="col">
                <a href="{{ route('exports.pafupload', $model->id) }}" class="btn">Export Upload CSV</a>
            </div>
            <div class="col">
                <a href="{{ route('exports.pafuploadwithmcb', $model->id) }}" class="btn">Export Upload With MCB CSV</a>
            </div>
        </div>
        @endif

        @if ($model->comment)
        <div class="row">
            <div class="col-xl-12">
                <div class="info-box">
                    <p>MCB Authorization / comment</p>
                    <small>{{ $model->comment }}</small>
                </div>
            </div>
        </div>
        @endif
    </div>

    @include('partials.signoffs.view-history')
</div>
@endsection
