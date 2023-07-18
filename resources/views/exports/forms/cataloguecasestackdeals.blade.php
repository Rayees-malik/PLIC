<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Catalogue Case Stack Deals (InDesign)</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'cataloguecasestackdeals') }}">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="input-wrap">
                                <label>Promo Period
                                    <div class="icon-input">
                                        <select name="period_id1" class="searchable" data-placeholder="Select Period">
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
                        <div class="row">
                            <div class="input-wrap">
                                <label>Promo Period
                                    <div class="icon-input">
                                        <select name="period_id2" class="searchable" data-placeholder="Optional">
                                            <option value disabled selected>Optional</option>
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
                        <div class="row input-wrap">
                            <label>Options</label>
                            <div class="checkbox-wrap">
                                <label class="checkbox">
                                    <input type="checkbox" name="grocery_only" value="1">
                                    <span class="checkbox-checkmark"></span>
                                    <span class="checkbox-label">Grocery Only</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="primary-btn block-btn mt-3" title="Export">
                        <i class="material-icons">save_alt</i>
                        Export
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
