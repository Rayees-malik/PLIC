@if ($model->hasRegulations)
<h3 class="js-review-toggle review-toggle {{ $errors->regulatory->count() ? 'open error' : '' }}">Regulatory</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->regulatory->count() ? 'error' : '' }}">
        @if ($model->hasNPN)
        <div class="row">
            <div class="col-xl-4">
                <h4>NPN / DIN-HM</h4>
                @include('partials.review.field', ['field' => 'npn', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Date Issued (NPN / DIN-HM)</h4>
                @include('partials.review.field', ['field' => 'npn_issued', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        @endif
        @if ($model->requiresImporter)
        <div class="row">
            <div class="col-xl-4">
                <h4>Importer of Record</h4>
                <div class="review-field">{{ optional($model->regulatoryInfo)->importer_is_purity == 0 ? 'Other' : 'Purity' }}</div>
            </div>
        </div>
        @if (optional($model->regulatoryInfo)->importer_is_purity == 0)
        <div class="row">
            <div class="col-xl-4">
                <h4>Importer Name</h4>
                @include('partials.review.field', ['field' => 'importer_name', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Importer Phone Number</h4>
                @include('partials.review.field', ['field' => 'importer_phone', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Importer Email Address</h4>
                @include('partials.review.field', ['field' => 'importer_email', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        @endif
        @endif
        @if ($model->requiresCNN)
        <div class="row">
            <div class="col-xl-4">
                <h4>Cosmetic Notification Number</h4>
                @include('partials.review.field', ['field' => 'cosmetic_notification_number', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        @endif
        @if ($model->requiresCosmeticLicense)
        <div class="row">
            <div class="col-xl-4">
                <h4>Cosmetic License</h4>
                @include('partials.review.media-field', ['collection' => 'cosmetic_license'])
            </div>
        </div>
        @endif
        @if ($model->isMedicalDevice)
        <div class="row">
            <div class="col-xl-4">
                <h4>Medical Class</h4>
                <div class="review-field">{{ optional($model->regulatoryInfo)->medical_class == '1' ? 'Class I' : 'Class II' }}</div>
            </div>
            @if (optional($model->regulatoryInfo)->medical_class == '2')
            <div class="col-xl-4">
                <h4>Medical Device Establishment #</h4>
                @include('partials.review.field', ['field' => 'medical_device_establishment_id', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Medical Device License Id</h4>
                @include('partials.review.field', ['field' => 'medical_device_establishment_license_id', 'model' => $model->regulatoryInfo])
            </div>
            @endif
        </div>
        @if (optional($model->regulatoryInfo)->medical_class == '2')
        <div class="row">
            <div class="col-xl-4">
                <h4>Medical Device License</h4>
                @include('partials.review.media-field', ['collection' => 'medical_device_establishment_license'])
            </div>
        </div>
        @endif
        @endif
        @if ($model->isPesticide)
        <div class="row">
            <div class="col-xl-4">
                <h4>Pesticide Class</h4>
                <div class="review-field">{{ optional($model->regulatoryInfo)->medical_class == '5' ? 'Class 5' : 'Class 6' }}</div>
            </div>
            <div class="col-xl-4">
                <h4>PCA Number (Federal)</h4>
                @include('partials.review.field', ['field' => 'pca_number', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        @endif

        @if ($model->hasNutritionalInfo)
        <div class="row">
            <div class="col-xl-4">
                <h4>Serving Size</h4>
                @include('partials.review.field', ['field' => 'serving_size', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Calories</h4>
                @include('partials.review.field', ['field' => 'calories', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Total Fat</h4>
                @include('partials.review.field', ['field' => 'total_fat', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Trans Fat</h4>
                @include('partials.review.field', ['field' => 'trans_fat', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Saturated Fat</h4>
                @include('partials.review.field', ['field' => 'saturated_fat', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Cholesterol</h4>
                @include('partials.review.field', ['field' => 'cholesterol', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Sodium</h4>
                @include('partials.review.field', ['field' => 'sodium', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Total Carbohydrates</h4>
                @include('partials.review.field', ['field' => 'carbohydrates', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Fiber</h4>
                @include('partials.review.field', ['field' => 'fiber', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Sugar</h4>
                @include('partials.review.field', ['field' => 'sugar', 'model' => $model->regulatoryInfo])
            </div>
            <div class="col-xl-4">
                <h4>Protein</h4>
                @include('partials.review.field', ['field' => 'protein', 'model' => $model->regulatoryInfo])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <h4>Preventive Control Plan (PCP)</h4>
                @include('partials.review.media-field', ['collection' => 'preventive_control_plan'])
            </div>
        </div>
        @endif
    </div>
</div>
@endif
