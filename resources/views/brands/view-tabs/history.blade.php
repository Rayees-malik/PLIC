<div id="history-view">
    @if ($model->signoffs->count())
    <div class="row">
        <div class="col">
            <h3>Brand History</h3>
            <table class="table invoices-table">
                <thead>
                    <tr>
                        <th>Submitted By</th>
                        <th>Submitted On</th>
                        <th>Approved On</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                @foreach ($model->signoffs as $signoff)
                <tr>
                    <td>
                        {{ $signoff->user->name }}</td>
                    <td>{{ $signoff->submitted_at->toFormattedDateString() }}</td>
                    <td>{{ $signoff->updated_at->toFormattedDateString() }}</td>
                    <td><a href="{{ route('brands.show', $signoff->proposed_id) }}" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a></td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
    @if ($model->discoRequests->count())
    <div class="row">
        <div class="col">
            <h3>Disco History</h3>
            <table class="table invoices-table">
                <thead>
                    <tr>
                        <th>Submitted By</th>
                        <th>Submitted On</th>
                        <th>Approved On</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                @foreach ($model->discoRequests as $discoRequest)
                @foreach ($discoRequest->signoffs as $signoff)
                <tr>
                    <td>
                        {{ $signoff->user->name }}</td>
                    <td>{{ $signoff->submitted_at->toFormattedDateString() }}</td>
                    <td>{{ $signoff->updated_at->toFormattedDateString() }}</td>
                    <td><a href="{{ route('branddiscos.show', $signoff->proposed_id) }}" class="link-btn table-btn"><i class="material-icons">remove_red_eye</i>View</a></td>
                </tr>
                @endforeach
                @endforeach
            </table>
        </div>
    </div>
    @endif
</div>
