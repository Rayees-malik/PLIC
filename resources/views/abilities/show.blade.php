@extends('layouts.app')

@section('page', 'View Ability')

@section('content')
<div class="container container-xxl">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-6 col-md-8">
            <h1 class="text-center">View Ability</h1>
            <div class="container">
                <div class="tabs-wrap">
                    <div class="tabs-header">
                        <div class="tab-btn tab-selected" name="general">General</div>
                    </div>
                    <div class="tabs-body">
                        <div class="general-tab">
                            @include("abilities.view-tabs.general")
                        </div>
                    </div>
                    <div class="bottom-controls mb-3">
                        <a class="link-btn" href="{{ route('abilities.index') }}" title="Return to Index">
                            <i class="material-icons">arrow_back</i>
                            Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/view-tabs.js') }}"></script>
@endpush
