<div class="card mt-1">
    <h4 class="card-header">Signoff Responses</h4>
    <div class="card-body">
        <table class="table table-striped">
            <thead class="thead-light">
                <tr>
                    <th scope="col">User</th>
                    <th scope="col">Date</th>
                    <th scope="col">Comment</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->signoff->responses as $response)
                @if (!auth()->user()->isVendor || ($response->step == 1 && !$response->approved))
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
                @endif
                @endforeach
            </tbody>
        </table>
        @if (!auth()->user()->isVendor)
        <div class="approved-rejected-wrap">
            <div class="approved">
                Approved
            </div>
            <div class="rejected">
                Rejected
            </div>
        </div>
        @endif
    </div>
</div>
