@extends('layouts.app')

@section('page', 'Catalogue Categories')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-6 col-md-8">
            <h1 class="text-center mb-1">Catalogue Categories</h1>
            <h2 class="mb-5 text-center"><a href="{{ route('brands.show', $model->id) }}">{{ $model->name }}</a></h2>
        </div>

        @can('brand.edit.categories')
        @include('brands.categories.line-item-table')
        @else
        @include('brands.categories.show-line-item-table')
        @endif
    </div>
</div>
@endsection
