<div id="uploads" class="js-stepper-step stepper-step">
    {!! BladeHelper::uploaderModelFields($model) !!}
    @if (!$model->id)
    <div class="row mb-4">
        @include('partials.errors.error-flash', ['message' => 'Brand name is required to upload files.'])
    </div>
    @endif
    <div class="row">
        <div class="col-xl-6">
            <h3>Main Logo</h3>
            {!! BladeHelper::uploaderField($model, 'logo', ['allowRestore' => $signoffForm ?? false]) !!}
        </div>
        <div class="col-xl-6">
            <h3>Alternative Logos</h3>
            {!! BladeHelper::uploaderField($model, 'alternative_logo', ['limit' => 3, 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <h3>Food Administration License
                <div class="tooltip-wrap">
                    <div class="tooltip-icon" data-toggle="popover" title="Notice" data-content="Required only if you will be selling food products.">
                        <i class="material-icons">info</i>
                    </div>
                </div>
            </h3>
            {!! BladeHelper::uploaderField($model, 'food_administration_license', ['limit' => 3, 'extensions' => 'pdf', 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
        <div class="col-xl-6">
            <h3>
                Third Party Facility Certification
                <div class="tooltip-wrap">
                    <div class="tooltip-icon" data-toggle="popover" title="Notice" data-content="Required only if you will be selling food products.">
                        <i class="material-icons">info</i>
                    </div>
                </div>
            </h3>
            {!! BladeHelper::uploaderField($model, 'facility_certification', ['limit' => 3, 'extensions' => "pdf", 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
    </div>
</div>
