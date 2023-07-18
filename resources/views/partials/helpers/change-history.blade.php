@foreach ($tables as $key => $rows)
<div class="js-change-{{ $key }}" style="display: none;">
    <div class="container">
        <div class="row change-history-header">
            <div class="col-4">Date</div>
            <div class="col-3">User</div>
            <div class="col-5">Change</div>
        </div>
        @foreach ($rows as $row)
        <div class="row change-history-body">
            <div class="col-4">{{ $row['timestamp'] }}</div>
            <div class="col-3">{{ $row['user'] }}</div>
            <div class="col-5">{{ $row['value'] }}</div>
        </div>
        @endforeach
    </div>
</div>
@endforeach
