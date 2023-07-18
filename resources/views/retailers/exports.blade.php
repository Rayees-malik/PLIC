@extends('layouts.app')

@section('page', 'Retailer Exports')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1 class="mb-0">Retailer Exports</h1>
            <h2 class="mb-5"><a href="{{ route('retailers.show', $model->id) }}">{{ $model->name }}</a></h2>
        </div>

        @foreach (glob(base_path() . '/resources/views/retailers/exports/*.blade.php') as $file)
        @include('retailers.exports.' . basename(str_replace('.blade.php', '', $file)))
        @endforeach

        <div class="bottom-controls mb-3">
            <a class="link-btn" href="{{ route('retailers.show', $model->id) }}" title="Return to Index">
                <i class="material-icons">arrow_back</i>
                Back
            </a>
        </div>
    </div>
</div>
@endsection


@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
