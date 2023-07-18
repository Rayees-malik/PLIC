@extends('layouts.app')

@section('page', 'Notifications')

@section('content')
<div class="container container-xxl js-notifications-container">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Notifications</h2>
        </div>
        <div class="tabs-wrap">
            <div class="tabs-header">
                <a class="tab-btn {{ $selected == 'unread' ? 'tab-selected' : '' }}" href="{{ route('notifications.index') }}">
                    Unread
                </a>
                <a class="tab-btn {{ $selected == 'read' ? 'tab-selected' : '' }}" href="{{ route('notifications.index', ['filter' => 'read']) }}">
                    Read
                </a>
            </div>
            <div class="tabs-body">
                <div class="table-responsive-xl">
                    {{ $datatable->table() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/notifications.js') }}"></script>
{{ $datatable->scripts() }}
@endpush
