@extends('layouts.app')

@section('page', 'Manage Disco Promos')

@section('content')
<div class="container container-xxl">
    <h1 class="text-center">Manage Disco Promos</h1>
    <div class="card">
        <div class="card-body">
            @can('promo.update.discos')
                @include('discopromos.form')
            @endcan
        </div>
    </div>
</div>
@endsection
