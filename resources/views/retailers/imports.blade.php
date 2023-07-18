@extends('layouts.app')

@section('page', 'Retailer Imports')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 text-center">
            <h1 class="mb-0">Retailer Imports</h1>
            <h2 class="mb-5"><a href="{{ route('retailers.show', $model->id) }}">{{ $model->name }}</a></h2>
        </div>

        <div class="col-xl-6 col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between flex-wrap">
                    <h2 class="mb-0">Approved Product Listings</h2>
                </div>
                <div class="card-body">
                    <div class="formContainer">
                        <form method="POST" action="{{ route('retailers.imports.listings', $model->id) }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="listings" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                            <button type="submit" class="primary-btn block-btn mt-3" title="Import">
                                <i class="material-icons">save_alt</i>
                                Import Listings File
                            </button>
                            <small><em>This will overwrite any existing listings data from the previous import.</em></small>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-controls mb-3">
            <a class="link-btn" href="{{ route('retailers.show', $model->id) }}" title="Return to Index">
                <i class="material-icons">arrow_back</i>
                Back
            </a>
        </div>
    </div>
</div>
@endsection