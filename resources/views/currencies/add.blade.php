@extends('layouts.app')

@section('page', 'Add Currency')

@section('content')
<div class="container text-center">
    <div class="row justify-content-center">
        <div class="col-xl-5 col-md-8">
            <div class="card text-center">
              <h2 class="card-header">Add Currency</h2>
              <div class="card-body">
                  @include('currencies.form')
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
