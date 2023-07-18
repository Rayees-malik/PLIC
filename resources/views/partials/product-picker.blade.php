<div class="product-picker">
    <div class="row">
        <div class="col">
            <h4>By Stock Id</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-3 col-xl-2">
            <label>Stock Id</label>
        </div>
        <div class="col-6 input-wrap">
            <select class="js-pp-stockid ajax" multiple data-placeholder="By Stock Id">
            </select>
        </div>
        <div class="col-3 col-xl-2">
            <button type="button" class="btn block-btn primary-btn js-pp-stockid-button disabled-btn" disabled>Add Product</button>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <h4>By Brand/Category</h4>
        </div>
    </div>

    <div class="row">
        <div class="col-3 col-xl-2">
            <label class="mt-1">Brand</label>
        </div>
        <div class="col-6 col-xl-6 dropdown-wrap">
            <div class="dropdown-icon">
                <select class="js-pp-brand searchable" data-placeholder="By Brand" {{ isset($hideLineDrives) ? '' : 'multiple' }}>
                    <option>Loading...</option>
                </select>
            </div>
        </div>
        @if (!isset($hideLineDrives))
        <div class="col-3 col-xl-2">
            <button type="button" class="btn block-btn primary-btn js-pp-brand-button disabled-btn" disabled>Add Line Drive</button>
        </div>
        @endif
    </div>

    <div class="js-pp-category-row row" style="display: none;">
        <div class="col-3 col-xl-2">
            <label class="mt-1">Category</label>
        </div>
        <div class="col-6 dropdown-wrap">
            <div class="dropdown-icon">
                <select class="js-pp-category searchable" multiple data-placeholder="By Category">
                    <option>Loading...</option>
                </select>
            </div>
        </div>
        <div class="col-3 col-xl-2">
            <button type="button" class="btn block-btn primary-btn js-pp-category-button disabled-btn" disabled>Add Category</button>
        </div>
    </div>

    <div class="js-pp-product-row row" style="display: none;">
        <div class="col-3 col-xl-2">
            <label class="mt-1">Product</label>
        </div>
        <div class="col-6 input-wrap">
            <select class="js-pp-product searchable" multiple data-placeholder="By Product">
                <option>Loading...</option>
            </select>
        </div>
        <div class="col-3 col-xl-2">
            <button type="button" class="btn block-btn primary-btn js-pp-product-button disabled-btn" disabled>Add Product</button>
        </div>
    </div>
</div>


@push('scripts')
{!! BladeHelper::initChosenSelect(['searchable', 'ajax']) !!}
<script type="text/javascript" src="{{ mix('js/product-picker.js') }}"></script>
@endpush
