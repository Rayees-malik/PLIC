<div id="regulatory-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Category</p>
                <h4>{{ optional($model->category)->name ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Receive Attribute</p>
                <h4>{{ $model->receiveAttribute ?? '-' }}</h4>
            </div>
        </div>
    </div>

    @if ($model->hasNPN)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>NPN / DIN-HM</p>
                <h4>{{ optional($model->regulatoryInfo)->npn ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Date Issued (NPN / DIN-HM)</p>
                <h4>{{ optional($model->regulatoryInfo)->npn_issued ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->requiresCosmeticLicense)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Cosmetic License</p>
                @if ($model->getMedia('supplements_license')->first())
                {!! $model->getMedia('supplements_license')->first()->getDownloadLink(null, 'h4'); !!}
                @else
                <h4>Missing</h4>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if ($model->requiresImporter)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Importer of Record</p>
                <h4>{{ optional($model->regulatoryInfo)->importer_is_purity == 0 ? 'Other' : 'Purity' }}</h4>
            </div>
        </div>
    </div>
    @if (optional($model->regulatoryInfo)->importer_is_purity == 0)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Importer Name</p>
                <h4>{{ optional($model->regulatoryInfo)->importer_name ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Importer Phone Number</p>
                <h4>{{ optional($model->regulatoryInfo)->importer_phone ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Importer Email Address</p>
                <h4>{{ optional($model->regulatoryInfo)->importer_email ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif


    @if ($model->requiresCNN)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Cosmetic Notification Number</p>
                <h4>{{ optional($model->regulatoryInfo)->cosmetic_notification_number ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->isMedicalDevice)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Medical Class</p>
                <h4>{{ optional($model->regulatoryInfo)->medical_class == '1' ? 'Class I' : 'Class II' }}</h4>
            </div>
        </div>
        @if (optional($model->regulatoryInfo)->medical_class == '2')
        <div class="col-xl-4">
            <div class="info-box">
                <p>Medical Device Establishment #</p>
                <h4>{{ optional($model->regulatoryInfo)->medical_device_establishment_id ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Medical Device License Id</p>
                <h4>{{ optional($model->regulatoryInfo)->medical_device_establishment_license_id ?? '-' }}</h4>
            </div>
        </div>
        @endif
    </div>
    @if (optional($model->regulatoryInfo)->medical_class == '2' && $model->getMedia('medical_device_establishment_license')->count())
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Medical Device License</p>
                <a href="{{ $model->getFirstMediaUrl('medical_device_establishment_license') }}" title="Download" download="">
                    <h4>Download</h4>
                </a>
            </div>
        </div>
    </div>
    @endif
    @endif

    @if ($model->isPesticide)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Pesticide Class</p>
                <h4>{{ optional($model->regulatoryInfo)->medical_class == '1' ? 'Class 5' : 'Class 6' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>PCA Number (Federal)</p>
                <h4>{{ optional($model->regulatoryInfo)->pca_number ?? '-' }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if ($model->hasNutritionalInfo)
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Serving Size</p>
                <h4>{{ optional($model->regulatoryInfo)->serving_size ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Calories</p>
                <h4>{{ optional($model->regulatoryInfo)->calories ?? '-' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Total Fat</p>
                <h4>{{ optional($model->regulatoryInfo)->total_fat ?? '-' }}g</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Trans Fat</p>
                <h4>{{ optional($model->regulatoryInfo)->trans_fat ?? '-' }}g</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Saturated Fat</p>
                <h4>{{ optional($model->regulatoryInfo)->saturated_fat ?? '-' }}g</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Cholesterol</p>
                <h4>{{ optional($model->regulatoryInfo)->cholesterol ?? '-' }}mg</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Sodium</p>
                <h4>{{ optional($model->regulatoryInfo)->sodium ?? '-' }}mg</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Total Carbohydrates</p>
                <h4>{{ optional($model->regulatoryInfo)->carbohydrates ?? '-' }}g</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Fiber</p>
                <h4>{{ optional($model->regulatoryInfo)->fiber ?? '-' }}g</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Sugar</p>
                <h4>{{ optional($model->regulatoryInfo)->sugar ?? '-' }}g</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Protein</p>
                <h4>{{ optional($model->regulatoryInfo)->protein ?? '-' }}g</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Preventive Control Plan (PCP)</p>
                @if ($model->getMedia('preventive_control_plan')->first())
                {!! $model->getMedia('preventive_control_plan')->first()->getDownloadLink(null, 'h4'); !!}
                @else
                <h4>Missing</h4>
                @endif
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
