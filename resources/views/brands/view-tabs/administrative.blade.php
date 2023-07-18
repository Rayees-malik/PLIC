<div id="administrative-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Status</p>
                <h4>{{ App\Helpers\StatusHelper::toString($model->status) }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>AS400 Category Code</p>
                <h4>{{ $model->category_code }}</h4>
            </div>
        </div>
        @if ($model->as400_category)
        <div class="col-xl-4">
            <div class="info-box">
                <p>AS400 Category</p>
                <h4>{{ $model->as400_category }}</h4>
            </div>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Education Portal</p>
                <h4>{{ $model->education_portal ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Hide From Exports</p>
                <h4>{{ $model->hide_from_exports ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Finance Brand Number</p>
                <h4>{{ $model->finance_brand_number }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Add'l PL Promo Discount</p>
                <h4>{{ $model->default_pl_discount ?? '0' }}%</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Allow OI Promos</p>
                <h4>{{ $model->allow_oi ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="info-box">
                <p>Catalogue Notice</p>
                <h4>{{ $model->catalogue_notice }}</h4>
            </div>
        </div>
    </div>

    <h3 class="form-section-title">MCB Approval</h3>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Nutrition House</p>
                <h4>{{ $model->nutrition_house ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
        @if ($model->nutrition_house)
        <div class="col-xl-4">
            <div class="info-box">
                <p>Nutrition House Payment Type</p>
                <h4>{{ ucfirst($model->nutrition_house_payment_type ?? 'Vendor') }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Nutrition House Payment</p>
                <h4>{{ $model->nutrition_house_payment ?? '7' }}</h4>
            </div>
        </div>
        @endif
    </div>

    @if ($model->nutrition_house && $model->nutrition_house_payment_type == 'purity')
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Nutrition House Percentage</p>
                <h4>{{ $model->nutrition_house_percentage }}%</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Nurtition House Purity Life Percentage</p>
                <h4>{{ $model->nutrition_house_purity_percentage }}%</h4>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Health First</p>
                <h4>{{ $model->health_first ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
        @if ($model->health_first)
        <div class="col-xl-4">
            <div class="info-box">
                <p>Health First Payment Type</p>
                <h4>{{ ucfirst($model->health_first_payment_type ?? 'Vendor') }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Health First Payment</p>
                <h4>{{ $model->health_first_payment ?? '3' }}</h4>
            </div>
        </div>
        @endif
    </div>

    @if ($model->health_first && $model->health_first_payment_type == 'purity')
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Health First Percentage</p>
                <h4>{{ $model->health_first_percentage }}%</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Health First Purity Life Percentage</p>
                <h4>{{ $model->health_first_purity_percentage }}%</h4>
            </div>
        </div>
    </div>
    @endif
</div>
