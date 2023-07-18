<div class="col-xl-6 mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h2 class="mb-0">Catalogue (Quarterly)</h2>
    </div>
    <div class="card-body">
      <div class="formContainer">
        <form method="POST" action="{{ route('exports.export', 'catalogue') }}">
          @csrf
          <div class="container">
            <div class="row">
              <div class="input-wrap">
                <label>Promo Period 1
                  <div class="icon-input">
                    <select name="period_id1" class="searchable" data-placeholder="Select Period 1">
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
                <label>Promo Period 2
                  <div class="icon-input">
                    <select name="period_id2" class="searchable" data-placeholder="Select Period 2 (Optional)">
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
            <div class="row">
              <div class="input-wrap">
                <label>Promo Period 3
                  <div class="icon-input">
                    <select name="period_id3" class="searchable" data-placeholder="Select Period 3 (Optional)">
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
            <div class="row">
              <div class="input-wrap">
                <label>New Products From
                  <div class="icon-input">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input name="date_start" class="js-datepicker" value="" readonly>
                  </div>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="input-wrap">
                <label>New Products To
                  <div class="icon-input">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input name="date_end" class="js-datepicker" value="" readonly>
                  </div>
                </label>
              </div>
            </div>
            <div class="row">
              <div class="input-wrap">
                <label>By Brand(s)
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
