<h3 class="form-section-title">Account Managers</h3>
<div id="purchasing-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Purchasing Specialist</p>
                <h4>{{ optional($model->purchasingSpecialist)->name ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Vendor Relations Specialist</p>
                <h4>{{ optional($model->vendorRelationsSpecialist)->name ?? '-' }}</h4>
            </div>
        </div>
    </div>
</div>

<h3 class="form-section-title">Ordering</h3>
<div class="row">
    <div class="col-xl-4">
        <div class="info-box">
            <p>Minimum Order</p>
            <h4>{{ $model->minimum_order_type == '$' ? "\${$model->minimum_order_quantity}" : "{$model->minimum_order_quantity} " . Str::plural('Unit', $model->minimum_order_quantity) }}</h4>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="info-box">
            <p>Shipping Lead Time</p>
            <h4>{{ $model->shipping_lead_time ?? '-' }}</h4>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="info-box">
            <p>Product Availability</p>
            <h4>{{ $model->product_availability ?? '-' }}</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="info-box">
            <p>Unpublished New Listing Deal</p>
            <h4>{{ $model->unpublished_new_listing_deal ?? '-' }}</h4>
        </div>
    </div>
</div>
