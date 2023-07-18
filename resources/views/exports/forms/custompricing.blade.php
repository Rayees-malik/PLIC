<div class="col-xl-6 mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h2 class="mb-0">Custom Pricing</h2>
    </div>
    <div class="card-body">
      <div class="formContainer">
        <form method="POST" action="{{ route('exports.export', 'custompricing') }}">
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
            <div class="row input-wrap">
              <label>Options</label>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="include_costs" value="1" checked>
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Include Costs</span>
                </label>
              </div>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="include_noncatalogue" value="1">
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Include Non-catalogue Products</span>
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
