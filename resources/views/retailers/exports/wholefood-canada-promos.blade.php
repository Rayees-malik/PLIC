@if ($model->id == 23)
<div class="col-xl-6 col-md-8">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Wholefoods Promo Export</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('retailers.exports.wfcanadapromo', $model->id) }}">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="input-wrap">
                                <label>Promo Period
                                    <div class="icon-input">
                                        <select name="period_id" class="searchable" data-placeholder="Select Period">
                                            @foreach ($promoPeriods as $period)
                                            <option value="{{ $period->id }}">
                                                {{ $period->name }} ({{ $period->start_date->toFormattedDateString() }} - {{ $period->end_date->toFormattedDateString() }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="primary-btn block-btn mt-3" title="Import">
                        <i class="material-icons">save_alt</i>
                        Export
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
