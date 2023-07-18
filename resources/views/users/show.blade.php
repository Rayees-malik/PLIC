@extends('layouts.app')

@section('page', 'View User')

@section('content')

<div class="container view-page-wrap">
    <div class="view-page-sticky">
        <div class="info-box">
            <h2>{{ $model->name }}</h2>
        </div>
        <div class="info-box">
            <h2>{{ $model->email }}</h2>
        </div>

        @can('admin')
        <div class="info-box info-box-pull-right">
            <h2><a href="{{ route('impersonate', $model->id) }}" class="btn">Impersonate User</a></h2>
        </div>
        @endcan

        <div class="info-box info-box-pull-right">
            <h2>
                @if ($model->broker)
                <a href="{{ route('brokers.show', $model->broker->id) }}">{{ $model->broker->name }}</a>
                @elseif ($model->vendor)
                <a href="{{ route('vendors.show', $model->vendor->id) }}">{{ $model->vendor->name }}</a>
                @else
                @if ($model->isbroker)
                No Broker Assigned
                @elseif ($model->isVendor)
                No Vendor Assigned
                @else
                Purity Employee
                @endif
                @endif
            </h2>
        </div>
    </div>

    <div class="view-page-body">
        <div class="view-page-header">
            <div class="row">
                <div class="col-12">
                    <div class="info-box">
                        <p>Roles</p>
                        @foreach ($model->roles as $role)
                        <h2>{{ $role->title }}</h2>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
