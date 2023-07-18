<div class="col-xl-6 mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h2 class="mb-0">Promo Calendar</h2>
    </div>
    <div class="card-body">
      <div class="formContainer">
        <form method="POST" action="{{ route('exports.export', 'promocalendar') }}">
          @csrf
          <div class="container">
            <div class="row">
              <div class="input-wrap">
                <label>Year
                  <div class="icon-input">
                    <select name="year">
                      <option value="{{ date("Y") - 1 }}">{{ date("Y") - 1 }}</option>
                      <option value="{{ date("Y") }}" selected>{{ date("Y") }}</option>
                      <option value="{{ date("Y") + 1 }}">{{ date("Y") + 1 }}</option>
                    </select>
                  </div>
                </label>
              </div>
            </div>
            <div class="row input-wrap">
              <label>Options</label>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="include_unpublished" value="1" checked>
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Include Unpublished New Listing Deals</span>
                </label>
              </div>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="include_csd" value="1" checked>
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Include Case Stack Deals</span>
                </label>
              </div>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="include_french_csd" value="1">
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Include French Case Stack Deals</span>
                </label>
              </div>
              <div class="checkbox-wrap">
                <label class="checkbox">
                  <input type="checkbox" name="include_margin" value="1">
                  <span class="checkbox-checkmark"></span>
                  <span class="checkbox-label">Include Brand Margin</span>
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
