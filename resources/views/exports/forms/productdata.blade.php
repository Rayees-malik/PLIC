<div class="col-xl-6 mt-4">
  <div class="card">
    <div class="card-header d-flex justify-content-between flex-wrap">
      <h2 class="mb-0">Product Data</h2>
    </div>
    <div class="card-body">
      <div class="formContainer">
        <form method="POST" action="{{ route('exports.export', 'productdata') }}">
          @csrf
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
          <div class="checkbox-wrap">
            <label class="checkbox">
              <input type="checkbox" name="include_noncatalogue" value="1">
              <span class="checkbox-checkmark"></span>
              <span class="checkbox-label">Include Non-catalogue Products</span>
            </label>
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
