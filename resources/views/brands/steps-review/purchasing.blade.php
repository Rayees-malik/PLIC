<h3 class="js-review-toggle review-toggle {{ $errors->purchasing->count() ? 'open error' : '' }}">Purchasing</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->purchasing->count() ? 'error' : '' }}">

        @if (auth()->user()->can('edit', App\Models\Brand::class) && !auth()->user()->isVendor)
        <div class="row">
            <div class="col-xl-4">
                <h4>Purchasing Specialist</h4>
                @include('partials.review.field', ['field' => 'purchasingSpecialist', 'subfield' => 'name', 'formField' => 'purchasing_specialist_id'])
            </div>
            <div class="col-xl-4">
                <h4>Vendor Relations Specialist</h4>
                @include('partials.review.field', ['field' => 'vendorRelationsSpecialist', 'subfield' => 'name', 'formField' => 'vendor_relations_specialist_id'])
            </div>
        </div>
        @elseif ($model->purchasingSpecialist || $model->vendorRelationsSpecialist)
        <div class="row">
            @if ($model->purchasingSpecialist)
            <div class="col-xl-4">
                <h4>Purchasing Specialist</h4>
                @include('partials.review.field', ['field' => 'purchasingSpecialist', 'subfield' => 'name', 'formField' => 'purchasing_specialist_id'])
            </div>
            @endif
            @if ($model->vendorRelationsSpecialist)
            <div class="col-xl-4">
                <h4>Vendor Relations Specialist</h4>
                @include('partials.review.field', ['field' => 'vendorRelationsSpecialist', 'subfield' => 'name', 'formField' => 'vendor_relations_specialist_id'])
            </div>
            @endif
        </div>
        @endif
        <div class="row">
            <div class="col-xl-4">
                <h4>Minimum Order Quantity</h4>
                @include('partials.review.field', ['field' => 'minimum_order_quantity', 'prefix' => $model->minimum_order_type])
            </div>
            <div class="col-xl-4">
                <h4>Shipping Lead Time</h4>
                @include('partials.review.field', ['field' => 'shipping_lead_time'])
            </div>
            <div class="col-xl-4">
                <h4>Product Availability</h4>
                @include('partials.review.field', ['field' => 'product_availability'])
            </div>
        </div>
    </div>
</div>
