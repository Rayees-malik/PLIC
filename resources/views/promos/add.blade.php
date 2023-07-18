@extends('layouts.app')

@section('page', 'Create Promo')

@section('content')
<div class="container container-xxl">
    <h1 class="text-center {{ $owner ? 'mb-0' : '' }}">Create Promo</h1>
    @if ($owner)
    <h2 class="text-center"><a class="text-link" href="{{ route("{$owner->routePrefix}.show", $owner->id) }}">{{ $owner->displayName }}</a></h2>
    @endif
    @include('promos.form')
</div>
@endsection
