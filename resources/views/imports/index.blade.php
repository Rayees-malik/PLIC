@extends('layouts.app')

@section('page', 'Imports')

@section('content')
<div class="container">
    @foreach ($imports as $import)
    @include("imports.forms.{$import}")<br />
    @endforeach
</div>
@endsection