<div id="regulatory" class="js-stepper-step stepper-step" data-step="{{ $loop->iteration - 1 }}" data-hidden="{{ Arr::get($step, 'hidden', false) }}">
    <h3 class="form-section-title">
        Regulatory
        <span class="float-right">{{ $model->name }}{{ isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D' ? ' (RELIST)' : '' }}</span>
    </h3>

    @if (!auth()->user()->isVendor)
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <label>Receive Attribute
                <h2>{{ $model->receiveAttribute ?? '-' }}</h2>
            </label>
        </div>
    </div>
    @endif

    @if ($model->hasNPN)
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('npn') ? ' input-danger' : '' }}">
            <label>NPN / DIN-HM
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="npn" value="{{ old('npn', optional($model->regulatoryInfo)->npn) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('npn'))
            <small class="info-danger">{{ $errors->regulatory->first('npn') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('npn_issued') ? ' input-danger' : '' }}">
            <label>Date Issued (NPN / DIN-HM)
                <div class="icon-input">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input type="text" name="npn_issued" class="js-datepicker" value="{{ old('npn_issued', optional($model->regulatoryInfo)->npn_issued) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('npn_issued'))
            <small class="info-danger">{{ $errors->regulatory->first('npn_issued') }}</small>
            @endif
        </div>
    </div>
    @endif

    @if ($model->requiresImporter)
    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->regulatory->has('importer_is_purity') ? ' input-danger' : '' }}">
            <label>Importer of Record</label>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="importer_is_purity" class="js-importer" value="1" {{ old('importer_is_purity', optional($model->regulatoryInfo)->importer_is_purity) == 1 ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Purity Life</span>
                </label>
            </div>
            <div class="radio-wrap">
                <label class="radio">
                    <input type="radio" name="importer_is_purity" class="js-importer" value="0" {{ !old('importer_is_purity', optional($model->regulatoryInfo)->importer_is_purity) ? 'checked' : '' }}>
                    <span class="radio-checkmark"></span>
                    <span class="radio-label">Other</span>
                </label>
            </div>
        </div>
    </div>
    <div class="row js-importer-details" {!! old('importer_is_purity', optional($model->regulatoryInfo)->importer_is_purity) == 1 ? 'style="display: none"' : '' !!}>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('importer_name') ? ' input-danger' : '' }}">
            <label>Importer Name
                <div class="icon-input">
                    <i class="material-icons pre-icon">person</i>
                    <input type="text" name="importer_name" value="{{ old('importer_name', optional($model->regulatoryInfo)->importer_name) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('importer_name'))
            <small class="info-danger">{{ $errors->regulatory->first('importer_name') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('importer_phone') ? ' input-danger' : '' }}">
            <label>Importer Phone Number
                <div class="icon-input">
                    <i class="material-icons pre-icon">local_phone</i>
                    <input type="text" name="importer_phone" value="{{ old('importer_phone', optional($model->regulatoryInfo)->importer_phone) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('importer_phone'))
            <small class="info-danger">{{ $errors->regulatory->first('importer_phone') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('importer_email') ? ' input-danger' : '' }}">
            <label>Importer Email Address
                <div class="icon-input">
                    <i class="material-icons pre-icon">mail</i>
                    <input type="text" name="importer_email" value="{{ old('importer_email', optional($model->regulatoryInfo)->importer_email) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('importer_email'))
            <small class="info-danger">{{ $errors->regulatory->first('importer_email') }}</small>
            @endif
        </div>
    </div>
    @endif

    @if ($model->requiresCNN)
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('cosmetic_notification_number') ? ' input-danger' : '' }}">
            <label>Cosmetic Notification Number
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="cosmetic_notification_number" value="{{ old('cosmetic_notification_number', optional($model->regulatoryInfo)->cosmetic_notification_number) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('cosmetic_notification_number'))
            <small class="info-danger">{{ $errors->regulatory->first('cosmetic_notification_number') }}</small>
            @endif
        </div>
    </div>
    @endif

    @if ($model->requiresCosmeticLicense)
    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->regulatory->has('license') ? ' input-danger' : '' }}">
            <label>Cosmetic License</label>
            {!! BladeHelper::uploaderField($model, 'cosmetic_license', ['limit' => 3, 'extensions' => "pdf", 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
    </div>
    @endif

    @if ($model->isMedicalDevice)
    <div class="row mt-3">
        <div class="dropdown-wrap col-xl-4 {{ $errors->regulatory->has('medical_class') ? 'dropdown-danger' : '' }}">
            <label>Medical Class
                <div class="dropdown-icon">
                    <select name="medical_class" class="js-medical-class">
                        <option value="1" @if (optional($model->regulatoryInfo)->medical_class == "1") selected @endif>Class I</option>
                        <option value="2" @if (optional($model->regulatoryInfo)->medical_class == "2") selected @endif>Class II</option>
                    </select>
                </div>
            </label>
            @if ($errors->regulatory->has('medical_class'))
            <small class="info-danger">{{ $errors->regulatory->first('medical_class') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 js-medical-device {{ $errors->regulatory->has('medical_device_establishment_id') ? ' input-danger' : '' }}" {!! optional($model->regulatoryInfo)->medical_class == '2' ? '' : 'style="display: none;"' !!}>
            <label>Medical Device Establishment #
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="medical_device_establishment_id" value="{{ old('medical_device_establishment_id', optional($model->regulatoryInfo)->medical_device_establishment_id) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('medical_device_establishment_id'))
            <small class="info-danger">{{ $errors->regulatory->first('medical_device_establishment') }}</small>
            @endif
        </div>
    </div>
    <div class="row js-medical-device" {!! optional($model->regulatoryInfo)->medical_class == '2' ? '' : 'style="display: none;"' !!}">
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('medical_device_establishment_license_id') ? ' input-danger' : '' }}">
            <label>Medical Device License Id
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="medical_device_establishment_license_id" value="{{ old('medical_device_establishment_license_id', optional($model->regulatoryInfo)->medical_device_establishment_license_id) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('medical_device_establishment_license_id'))
            <small class="info-danger">{{ $errors->regulatory->first('medical_device_establishment_license_id') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->regulatory->has('medical_device_establishment_license_id') ? ' input-danger' : '' }}">
            <label>Upload Medical Device License</label>
            {!! BladeHelper::uploaderField($model, 'medical_device_establishment_license', ['limit' => 3, 'extensions' => "pdf", 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
    </div>
    @endif

    @if ($model->isPesticide)
    <div class="row mt-3">
        <div class="dropdown-wrap col-xl-4 {{ $errors->regulatory->has('pesticide_class') ? 'dropdown-danger' : '' }}">
            <label>Pesticide Class
                <div class="dropdown-icon">
                    <select name="pesticide_class">
                        <option value="5" @if (optional($model->regulatoryInfo)->pesticide_class == "5") selected @endif>Class 5</option>
                        <option value="6" @if (optional($model->regulatoryInfo)->pesticide_class == "6") selected @endif>Class 6</option>
                    </select>
                </div>
            </label>
            @if ($errors->regulatory->has('pesticide_class'))
            <small class="info-danger">{{ $errors->regulatory->first('pesticide_class') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('pca_number') ? ' input-danger' : '' }}">
            <label>PCA Number (Federal)
                <div class="icon-input">
                    <i class="material-icons pre-icon">crop_free</i>
                    <input type="text" name="pca_number" value="{{ old('pca_number', optional($model->regulatoryInfo)->pca_number) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('pca_number'))
            <small class="info-danger">{{ $errors->regulatory->first('pca_number') }}</small>
            @endif
        </div>
    </div>
    @endif

    @if ($model->hasNutritionalInfo)
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('serving_size') ? ' input-danger' : '' }}">
            <label>Serving Size
                <div class="icon-input">
                    <i class="material-icons pre-icon">food_bank</i>
                    <input type="text" name="serving_size" value="{{ old('serving_size', optional($model->regulatoryInfo)->serving_size) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('serving_size'))
            <small class="info-danger">{{ $errors->regulatory->first('serving_size') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('calories') ? ' input-danger' : '' }}">
            <label>Calories
                <div class="icon-input">
                    <i class="material-icons pre-icon">straighten</i>
                    <input type="text" name="calories" value="{{ old('calories', optional($model->regulatoryInfo)->calories) }}">
                </div>
            </label>
            @if ($errors->regulatory->has('calories'))
            <small class="info-danger">{{ $errors->regulatory->first('calories') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('total_fat') ? ' input-danger' : '' }}">
            <label>Total Fat
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="total_fat" value="{{ old('total_fat', optional($model->regulatoryInfo)->total_fat) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('total_fat'))
            <small class="info-danger">{{ $errors->regulatory->first('total_fat') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('trans_fat') ? ' input-danger' : '' }}">
            <label>Trans Fat
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="trans_fat" value="{{ old('trans_fat', optional($model->regulatoryInfo)->trans_fat) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('trans_fat'))
            <small class="info-danger">{{ $errors->regulatory->first('trans_fat') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('saturated_fat') ? ' input-danger' : '' }}">
            <label>Saturated Fat
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="saturated_fat" value="{{ old('saturated_fat', optional($model->regulatoryInfo)->saturated_fat) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('saturated_fat'))
            <small class="info-danger">{{ $errors->regulatory->first('saturated_fat') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('cholesterol') ? ' input-danger' : '' }}">
            <label>Cholesterol
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="mg">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="cholesterol" value="{{ old('cholesterol', optional($model->regulatoryInfo)->cholesterol) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('cholesterol'))
            <small class="info-danger">{{ $errors->regulatory->first('cholesterol') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('sodium') ? ' input-danger' : '' }}">
            <label>Sodium
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="mg">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="sodium" value="{{ old('sodium', optional($model->regulatoryInfo)->sodium) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('sodium'))
            <small class="info-danger">{{ $errors->regulatory->first('sodium') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('carbohydrates') ? ' input-danger' : '' }}">
            <label>Total Carbohydrates
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="carbohydrates" value="{{ old('carbohydrates', optional($model->regulatoryInfo)->carbohydrates) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('carbohydrates'))
            <small class="info-danger">{{ $errors->regulatory->first('carbohydrates') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('fiber') ? ' input-danger' : '' }}">
            <label>Fiber
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="fiber" value="{{ old('fiber', optional($model->regulatoryInfo)->fiber) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('fiber'))
            <small class="info-danger">{{ $errors->regulatory->first('fiber') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('sugar') ? ' input-danger' : '' }}">
            <label>Sugar
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="sugar" value="{{ old('sugar', optional($model->regulatoryInfo)->sugar) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('sugar'))
            <small class="info-danger">{{ $errors->regulatory->first('sugar') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->regulatory->has('protein') ? ' input-danger' : '' }}">
            <label>Protein
                <div class="icon-input">
                    <div class="placeholder" data-placeholder="g">
                        <i class="material-icons pre-icon">straighten</i>
                        <input type="text" name="protein" value="{{ old('protein', optional($model->regulatoryInfo)->protein) }}">
                    </div>
                </div>
            </label>
            @if ($errors->regulatory->has('protein'))
            <small class="info-danger">{{ $errors->regulatory->first('protein') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap {{ $errors->regulatory->has('preventive_control_plan') ? ' input-danger' : '' }}">
            <label>Upload Preventive Control Plan (PCP)</label>
            {!! BladeHelper::uploaderField($model, 'preventive_control_plan', ['limit' => 5, 'extensions' => "pdf", 'allowRestore' => $signoffForm ?? false]) !!}
        </div>
    </div>
    @endif
</div>
