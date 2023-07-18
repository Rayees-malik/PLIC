@extends('layouts.app')

@section('page', 'Listing Form Exports')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-xl-6 col-md-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between flex-wrap">
          <h2 class="mb-0">Export Listing Forms</h2>
        </div>

        <div class="card-body">
          <div class="formContainer">
            <form method="POST" action="{{ route('exports.listingforms.export') }}">
              @csrf
              <div class="container">
                <div class="row">
                  <div class="dropdown-wrap">
                    <label>Retailer</label>
                    <div class="dropdown-icon">
                      <select name="retailer" class="searchable" data-placeholder="Select Retailer">
                        @foreach ($forms as $form)
                        <option value="{{ $form }}">
                          {{ $form }}
                        </option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  @error('retailer')
                    <x-input.error>{{ $message }}</x-input.error>
                @enderror
                </div>
                <div class="row">
                  <div class="input-wrap">
                    <label>Stock Ids
                      <div class="input">
                        <textarea type="text" name="stock_ids" autocomplete="off"></textarea>
                        @error('stock_ids')
                  <x-input.error>{{ $message }}</x-input.error>
              @enderror
                      </div>
                    </label>
                  </div>

                </div>
                <div class="row">
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
  </div>
</div>
@endsection

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
