@extends('layouts.app')

@section('page', 'My Submissions')

@section('content')
<div class="container container-xxl">
    <div class="card">
        <div class="card-header">
            <h2 class="mb-0">Submissions</h2>
        </div>
        <div class="tabs-wrap">
            <div class="tabs-header">
                <a class="tw-relative tab-btn {{ $selected == 'rejected' ? 'tab-selected' : '' }}" href="{{ route('user.submissions', ['filter' => 'rejected']) }}">
                    <span class="tw-z-10">Rejections</span>

                    @if ($rejectedCount > 0)
                      <div class="tw-absolute tw-inline-flex tw-border-amber-600 tw-items-center tw-justify-center tw-w-2.5 tw-h-2.5 tw-bg-amber-400 tw-border-2 tw-rounded-full tw-top-4 tw-z-0 tw-right-2"></div>
                    @endif
                </a>
                <a class="tw-relative tab-btn {{ $selected == 'approved' ? 'tab-selected' : '' }}" href="{{ route('user.submissions', ['filter' => 'approved']) }}">
                    <span class="tw-z-10">Approved</span>
                </a>
                <a class="tw-relative tab-btn {{ $selected == 'pending' ? 'tab-selected' : '' }}" href="{{ route('user.submissions', ['filter' => 'pending']) }}">
                    <span class="tw-z-10">Pending Approval</span>
                    @if ($pendingCount > 0)
                      <div class="tw-absolute tw-inline-flex tw-border-amber-600 tw-items-center tw-justify-center tw-w-2.5 tw-h-2.5 tw-bg-amber-400 tw-border-2 tw-rounded-full tw-top-4 tw-z-0 tw-right-2"></div>
                    @endif
                </a>
                <a class="tw-relative tab-btn {{ $selected == 'draft' ? 'tab-selected' : '' }}" href="{{ route('user.submissions', ['filter' => 'draft']) }}">
                    <span class="tw-z-10">Drafts</span>
                </a>
                <a class="tw-relative tab-btn {{ $selected == 'outdated' ? 'tab-selected' : '' }}" href="{{ route('user.submissions', ['filter' => 'outdated']) }}">
                    <span class="tw-z-10">Outdated</span>
                </a>
            </div>
            <div class="tabs-body tw-space-y-4">
              <div class="tw-flex tw-justify-center">
                <div class="tw-italic tw-font-medium">Please note that any drafts or outdated signoffs that have not been modified in the last 30 days are automatically deleted.</div>
              </div>
              <div class="table-responsive-xl">
                 {!! $datatable->table() !!}
              </div>
            </div>
        </div>
    </div>
</div>
@include('modals.delete')
@include('modals.unsubmit')
@endsection

@push('scripts')
  {!! $datatable->scripts() !!}
@endpush

