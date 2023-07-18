@if ($signoff)
<div class="mt-3">
    <h3 class="mb-1">Signoff History</h3>

    <table class="table table-striped">
        <thead class="thead-light">
            <tr>
                <th scope="col">User</th>
                <th scope="col">Step</th>
                <th scope="col">Date</th>
                <th scope="col">Comment</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($signoff->responses as $response)
            <tr style="color: {{ $response->comment_only ? '#111111' : ($response->approved ? '#4caf50' : '#f44336') }};">
                <th scope="row">
                    {{ $response->user->name }}
                </th>
                <td>
                    {{ $response->comment_only && $response->comment == 'Submitted' ? '' : $signoff->signoffConfigSteps->where('step', $response->step)->first()->name }}
                </td>
                <td>
                    {{ $response->updated_at->format('Y-m-d g:ia') }}
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
@endif
