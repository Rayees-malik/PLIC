<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">Order Form</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'orderform') }}">
                    @csrf
                    <div class="container">
                        <div class="row">
                            <div class="input-wrap">
                                <label>By Brand
                                    <div class="icon-input">
                                        <select name="brand_id[]" class="searchable" data-placeholder="All Brands" multiple>
                                            @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">
                                                {{ $brand->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-wrap">
                                <label>or by Stock Id
                                    <div class="input">
                                        <textarea type="text" name="stock_ids" autocomplete="off"></textarea>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-wrap">
                                <label>Promo Period
                                    <div class="icon-input">
                                        <select name="period_id1" class="searchable" data-placeholder="Select Period">
                                            <option>Optional</option>
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
                                <label>Second Promo Period
                                    <div class="icon-input">
                                        <select name="period_id2" class="searchable" data-placeholder="Select Second Period">
                                            <option>Optional</option>
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
                            <label>Include UPC</label>
                            <div class="inline-radio-group">
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="upc" value="0" checked>
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">None</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="upc" value="1">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Product</span>
                                    </label>
                                </div>
                                <div class="radio-wrap">
                                    <label class="radio">
                                        <input type="radio" name="upc" value="2">
                                        <span class="radio-checkmark"></span>
                                        <span class="radio-label">Product + Case</span>
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
                            <label>Filters</label>
                            <div class="checkbox-wrap">
                                <label class="checkbox">
                                    <input type="checkbox" name="grocery_only" value="1">
                                    <span class="checkbox-checkmark"></span>
                                    <span class="checkbox-label">Grocery Only</span>
                                </label>
                            </div>
                            <div class="checkbox-wrap">
                                <label class="checkbox">
                                    <input type="checkbox" name="ondeal_only" value="1">
                                    <span class="checkbox-checkmark"></span>
                                    <span class="checkbox-label">On-deal Only</span>
                                </label>
                            </div>
                            <div class="checkbox-wrap">
                                <label class="checkbox">
                                    <input type="checkbox" name="include_noncatalogue" value="1">
                                    <span class="checkbox-checkmark"></span>
                                    <span class="checkbox-label">Include Non-catalogue Products</span>
                                </label>
                            </div>
                            <div class="input-wrap">
                                <label>Only Listed After
                                    <div class="icon-input">
                                        <i class="material-icons pre-icon">calendar_today</i>
                                        <input name="listed_after" class="js-datepicker" value="" readonly>
                                    </div>
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
