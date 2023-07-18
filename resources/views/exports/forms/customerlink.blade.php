<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Customer Link (Modular)</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'customerlink') }}">
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
                                            <option value selected>Optional</option>
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
                            <label>Product Status</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="product_status" value="" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">All</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="product_status" value="A">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Active</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="product_status" value="D">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Discontinued</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row input-wrap">
                            <label>Language</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="language" value="E" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">English</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="language" value="F">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">French</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row input-wrap">
                            <label>Export Type</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="export_type" value="M" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Modular</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="export_type" value="E">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Excel CSV</span>
                                    </label>
                                </div>
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
