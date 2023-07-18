<div class="col-xl-6 mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
            <h2 class="mb-0">AS400 Pricing Data</h2>
        </div>
        <div class="card-body">
            <div class="formContainer">
                <form method="POST" action="{{ route('exports.export', 'as400pricingdata') }}">
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
                        <div class="row input-wrap">
                          <label>Options</label>
                          <div class="checkbox-wrap">
                              <label class="checkbox">
                                  <input type="checkbox" name="include_disco" value="1">
                                  <span class="checkbox-checkmark"></span>
                                  <span class="checkbox-label">Include Disco Promos</span>
                              </label>
                          </div>
                          <div class="checkbox-wrap">
                              <label class="checkbox">
                                  <input type="checkbox" name="include_deal_summary_detail" value="1">
                                  <span class="checkbox-checkmark"></span>
                                  <span class="checkbox-label">Include Deal Summary Detail</span>
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
