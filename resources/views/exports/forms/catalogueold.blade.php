<div class="col-xl-6 mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h2 class="mb-0">Catalogue (Old Format)</h2>
    </div>
    <div class="card-body">
      <div class="formContainer">
        <form method="POST" action="{{ route('exports.export', 'catalogueold') }}">
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
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="new_only" value="1">
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">New Listings Only</span>
                </label>
              </div>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="exclude_disco" value="1">
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Exclude Disco</span>
                </label>
              </div>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="exclude_superseded" value="1">
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Exclude Superseded</span>
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
