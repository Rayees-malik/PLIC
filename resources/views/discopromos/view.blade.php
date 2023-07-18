@extends('layouts.app')

@section('page', 'View Disco Promos')

@section('content')
<div class="container container-xxl">
    <h1 class="text-center">View Disco Promos</h1>
    <div class="card">
        <div class="card-body">
            @can('promo.view.discos')
                @include('discopromos.form')
            @endcan
        </div>
    </div>
</div>
@endsection
