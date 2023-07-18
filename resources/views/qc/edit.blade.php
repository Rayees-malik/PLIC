@extends('layouts.app')

@section('page', 'QC')

@section('content')
<div class="tw-container tw-mx-auto tw-px-6">
  <livewire:quality-control-form :record="$record" />
</div>
@endsection
