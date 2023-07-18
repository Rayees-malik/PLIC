@extends('layouts.app')

@section('page', 'Add Promo Periods')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center {{ $owner ? 'mb-0' : '' }}">Add Promo Period</h1>
            @if ($owner)
            <h2 class="text-center"><a class="text-link" href="{{ route("{$owner->routePrefix}.show", $owner->id) }}">{{ $owner->displayName }}</a></h2>
            @endif
            <div class="card">
                <div class="card-body">
                    @include('promos.periods.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
