@extends('layouts.app')

@section('page', $signoff->getState() . " Signoff: {$model::getShortClassName()}")

@section('content')
<div class="container {{ $signoff->extraWide ? 'container-xxl' : '' }}">
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header text-center">
                    <h2 class="mb-1">{{ $signoff->getState() }} Signoff</h2>
                    <p class="mb-0 lead">{{ $model->displayName }}</p>
                </div>
                <div class="card-body">
                    @include($signoff->stepView, ['signoffForm' => true])
                    <div class="mt-3">
                        <p class="lead mb-0">Signoff History</p>
                        <table class="table table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Comment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($model->signoff->responses as $response)
                                <tr style="color: {{ $response->comment_only ? '#111111' : ($response->approved ? '#4caf50' : '#f44336') }};">
                                    <th scope="row">
                                        {{ $response->user->name }}
                                    </th>
                                    <td>
                                        {{ $response->updated_at->toDateString() }}
                                    </td>
                                    <td>
                                        @if ($response->comment)
                                        {{ $response->comment }}
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td scope="row" colspan="4">
                                        <em>No activity</em>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="approved-rejected-wrap">
                            <div class="approved">
                                Approved
                            </div>
                            <div class="rejected">
                                Rejected
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{!! BladeHelper::writeModelChanges($changes) !!}
@endsection
