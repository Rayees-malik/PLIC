<div id="details" class="js-stepper-step stepper-step">
    <h3 class="form-section-title">
        Details
        <span class="float-right">{{ $model->name }}{{ isset($signoffForm) && optional($model->as400StockData ?? $model->as400StockDataClone)->status == 'D' ? ' (RELIST)' : '' }}</span>
    </h3>
    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('tester_available') ? ' input-danger' : '' }}">
            <label>Tester Available to Order</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="tester_available" class="js-tester-available" value="0" {{ !old('tester_available', $model->tester_available) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="tester_available" class="js-tester-available" value="1" {{ old('tester_available', $model->tester_available) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
        </div>
        <div class="input-wrap col-xl-6 js-tester-code {{ $errors->details->has('tester_brand_stock_id') ? ' input-danger' : '' }}" {!! old('tester_available', $model->tester_available) ? '' : 'style="display: none"' !!}>
            <label>Tester Brand Product Code
                <div class="icon-input">
                    <i class="material-icons pre-icon">business</i>
                    <input type="text" name="tester_brand_stock_id" value="{{ old('tester_brand_stock_id', $model->tester_brand_stock_id) }}">
                </div>
            </label>
            @if ($errors->details->has('tester_brand_stock_id'))
            <small class="info-danger">{{ $errors->details->first('tester_brand_stock_id') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('brand_stock_id') ? ' input-danger' : '' }}">
            <label>Brand Product Code
                <div class="icon-input">
                    <i class="material-icons pre-icon">business</i>
                    <input type="text" name="brand_stock_id" value="{{ old('brand_stock_id', $model->brand_stock_id) }}">
                </div>
            </label>
            @if ($errors->details->has('brand_stock_id'))
            <small class="info-danger">{{ $errors->details->first('brand_stock_id') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-6 {{ $errors->details->has('flags') ? ' dropdown-danger' : '' }}">
            <label>Product Flags</label>
            <div class="dropdown-icon">
                <select name="flags[]" class="searchable" multiple data-placeholder="Select Flag(s)">
                    @foreach ($flags as $flag)
                    <option value="{{ $flag->id }}" {{ in_array($flag->id, Arr::wrap(old('flags', $model->flags->pluck('id')->toArray()))) ? 'selected' : '' }}>
                        {{ $flag->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @if ($errors->details->has('flags'))
            <small class="info-danger">{{ $errors->details->first('flags') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('description') ? ' input-danger' : '' }}">
            <label>Description
                <div class="input">
                    <textarea type="text" name="description" autocomplete="off">{{
                        old('description', $model->description)
                    }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('description'))
            <small class="info-danger">{{ $errors->details->first('description') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('description_fr') ? ' input-danger' : '' }}">
            <label>Description (FR)
                <div class="input">
                    <textarea type="text" name="description_fr" autocomplete="off">{{
                        old('description_fr', $model->description_fr)
                    }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('description_fr'))
            <small class="info-danger">{{ $errors->details->first('description_fr') }}</small>
            @endif
        </div>
    </div>

    <div class="row mt-3 mb-3">
        <div class="col-xl-6">
            <label class="mb-0">
                3 - 5 Key Product Features (EN)
                <div class="tooltip-wrap">
                    <div class="tooltip-icon" data-toggle="popover" title="Notice" data-content="For online retailers to use in their website listings">
                        <i class="material-icons">info</i>
                    </div>
                </div>
            </label>
            <ol class="features-list">
                @for ($i = 1; $i <= 5; $i++) <li>
                    <div class="input-wrap {{ $errors->details->has("features_{$i}") ? ' input-danger' : '' }}">
                        <input name="features_{{ $i }}" value="{{ old("features_{$i}", $model->{"features_{$i}"}) }}" class="mt-1">
                        @if ($errors->details->has("features_{$i}"))
                        <small class="info-danger">{{ $errors->details->first("features_{$i}") }}</small>
                        @endif
                    </div>
                    </li>
                    @endfor
            </ol>
        </div>
        <div class="col-xl-6">
            <label class="mb-0">
                3 - 5 Key Product Features (FR)
                <div class="tooltip-wrap">
                    <div class="tooltip-icon" data-toggle="popover" title="Notice" data-content="For online retailers to use in their website listings">
                        <i class="material-icons">info</i>
                    </div>
                </div>
            </label>
            <ol class="features-list">
                @for ($i = 1; $i <= 5; $i++) <li>
                    <div class="input-wrap {{ $errors->details->has("features_fr_{$i}") ? ' input-danger' : '' }}">
                        <input name="features_fr_{{ $i }}" value="{{ old("features_fr_{$i}", $model->{"features_fr_{$i}"}) }}" class="mt-1">
                        @if ($errors->details->has("features_{$i}"))
                        <small class="info-danger">{{ $errors->details->first("features_fr_{$i}") }}</small>
                        @endif
                    </div>
                    </li>
                    @endfor
            </ol>
        </div>
    </div>

    <div class="row mt-3">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('ingredients') ? ' input-danger' : '' }}">
            <label>Ingredients (EN)
                <div class="input">
                    <textarea type="text" name="ingredients" autocomplete="off">{{
                      old('ingredients', $model->ingredients)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('ingredients'))
            <small class="info-danger">{{ $errors->details->first('ingredients') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('ingredients_fr') ? ' input-danger' : '' }}">
            <label>Ingredients (FR)
                <div class="input">
                    <textarea type="text" name="ingredients_fr" autocomplete="off">{{
                      old('ingredients_fr', $model->ingredients_fr)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('ingredients_fr'))
            <small class="info-danger">{{ $errors->details->first('ingredients_fr') }}</small>
            @endif
        </div>
    </div>

    @if ($model->hasRecommendedUse)
    <div class="row mt-3">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('recommended_use') ? ' input-danger' : '' }}">
            <label>Recommended Use/Indications (EN)
                <div class="input">
                    <textarea type="text" name="recommended_use" autocomplete="off">{{
                      old('recommended_use', $model->recommended_use)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('recommended_use'))
            <small class="info-danger">{{ $errors->details->first('recommended_use') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('recommended_use_fr') ? ' input-danger' : '' }}">
            <label>Recommended Use/Indications (FR)
                <div class="input">
                    <textarea type="text" name="recommended_use_fr" autocomplete="off">{{
                      old('recommended_use_fr', $model->recommended_use_fr)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('recommended_use_fr'))
            <small class="info-danger">{{ $errors->details->first('recommended_use_fr') }}</small>
            @endif
        </div>
    </div>
    @endif
    @if ($model->hasRecommendedDosage)
    <div class="row mt-3">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('recommended_dosage') ? ' input-danger' : '' }}">
            <label>Recommended Dosage (EN)
                <div class="input">
                    <textarea type="text" name="recommended_dosage" autocomplete="off">{{
                        old('recommended_dosage', $model->recommended_dosage)
                    }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('recommended_dosage'))
            <small class="info-danger">{{ $errors->details->first('recommended_dosage') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('recommended_dosage_fr') ? ' input-danger' : '' }}">
            <label>Recommended Dosage (FR)
                <div class="input">
                    <textarea type="text" name="recommended_dosage_fr" autocomplete="off">{{
                        old('recommended_dosage_fr', $model->recommended_dosage_fr)
                    }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('recommended_dosage_fr'))
            <small class="info-danger">{{ $errors->details->first('recommended_dosage_fr') }}</small>
            @endif
        </div>
    </div>
    @endif

    @if ($model->hasWarnings)
    <div class="row mt-3">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('warnings') ? ' input-danger' : '' }}">
            <label>Cautions & Warnings (EN)
                <div class="input">
                    <textarea type="text" name="warnings" autocomplete="off">{{
                      old('warnings', $model->warnings)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('warnings'))
            <small class="info-danger">{{ $errors->details->first('warnings') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('warnings_fr') ? ' input-danger' : '' }}">
            <label>Cautions & Warnings (FR)
                <div class="input">
                    <textarea type="text" name="warnings_fr" autocomplete="off">{{
                      old('warnings_fr', $model->warnings_fr)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('warnings_fr'))
            <small class="info-danger">{{ $errors->details->first('warnings_fr') }}</small>
            @endif
        </div>
    </div>
    <div class="row mt-3">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('contraindications') ? ' input-danger' : '' }}">
            <label>Contraindications (EN)
                <div class="input">
                    <textarea type="text" name="contraindications" autocomplete="off">{{
                      old('contraindications', $model->contraindications)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('contraindications'))
            <small class="info-danger">{{ $errors->details->first('contraindications') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('contraindications_fr') ? ' input-danger' : '' }}">
            <label>Contraindications (FR)
                <div class="input">
                    <textarea type="text" name="contraindications_fr" autocomplete="off">{{
                      old('contraindications_fr', $model->contraindications_fr)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('contraindications_fr'))
            <small class="info-danger">{{ $errors->details->first('contraindications_fr') }}</small>
            @endif
        </div>
    </div>
    @endif

    <div class="row mt-3">
        <div class="input-wrap col-xl-6 {{ $errors->details->has('benefits') ? ' input-danger' : '' }}">
            <label>Benefits (EN)
                <div class="input">
                    <textarea type="text" name="benefits" autocomplete="off">{{
                      old('benefits', $model->benefits)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('benefits'))
            <small class="info-danger">{{ $errors->details->first('benefits') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-6 {{ $errors->details->has('benefits_fr') ? ' input-danger' : '' }}">
            <label>Benefits (FR)
                <div class="input">
                    <textarea type="text" name="benefits_fr" autocomplete="off">{{
                      old('benefits_fr', $model->benefits_fr)
                  }}</textarea>
                </div>
            </label>
            @if ($errors->details->has('benefits_fr'))
            <small class="info-danger">{{ $errors->details->first('benefits_fr') }}</small>
            @endif
        </div>
    </div>

    <div class="row mt-3">
        <div class="input-wrap col-sm-4 col-xl-2{{ $errors->details->has('shelf_life') ? ' input-danger' : '' }}">
            <label>Shelf Life
                <div class="icon-input">
                    <i class="material-icons pre-icon">calendar_today</i>
                    <input type="text" name="shelf_life" value="{{ old('shelf_life', $model->shelf_life) }}">
                </div>
            </label>
            @if ($errors->details->has('shelf_life'))
            <small class="info-danger">{{ $errors->details->first('shelf_life') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-sm-4 col-xl-2">
            <label>&nbsp;</label>
            <div class="dropdown-icon">
                <select name="shelf_life_units">
                    <option value="months" {{ old('shelf_life_units', $model->shelf_life_units) == 'months' ? 'selected' : '' }}>Months</option>
                    <option value="years" {{ old('shelf_life_units', $model->shelf_life_units) == 'years' ? 'selected' : '' }}>Years</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-xl-9">
            <h4 class="form-section-title mt-2">Product Allergens</h4>
            <div>
                <small><em>Notice: For 'Does Not Contain' only check the item(s) being advertised as (the allergen name)-free on the package.</em></small>
            </div>
            <div class="allergens-wrap mb-2">
                <div></div>
                <div><b>Contains</b></div>
                <div><b>May Contain</b></div>
                <div><b>Does Not Contain</b></div>
            </div>
            @foreach ($allergens as $allergen)
            <div class="allergens-wrap">
                <div>{{ $allergen->name }}</div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allergens[{{ $allergen->id }}]" value="1" {{ old("allergens.{$allergen->id}", $model->getAllergenStatus($allergen)) == 1 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label"></span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allergens[{{ $allergen->id }}]" value="0" {{ old("allergens.{$allergen->id}", $model->getAllergenStatus($allergen)) == 0 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label"></span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allergens[{{ $allergen->id }}]" value="-1" {{ old("allergens.{$allergen->id}", $model->getAllergenStatus($allergen)) == -1 ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label"></span>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <h4 class="form-section-title mt-2">Product Certifications</h4>
            @if (!$model->id)
            <div class="row mb-4">
                @include('partials.errors.error-flash', ['message' => 'Product name and category are required to upload files.'])
            </div>
            @endif
            <div class="row">
                @foreach ($certifications as $cert)
                <div class="col-6 js-cert-container">
                    <div class="switch-wrap">
                        <label class="switch">
                            <input type="checkbox" class="js-cert-switch" name="certifications[{{ $cert->id }}]" value="{{ $cert->id }}" {{
                                in_array($cert->id, Arr::wrap(old('certifications', $model->certifications->contains($cert->id) ? $cert->id : null))) ? 'checked' : ''
                            }}>
                            <span class="switch-checkmark"></span>
                            <span class="switch-label">{{ $cert->name }}</span>
                        </label>

                        @if ($errors->details->has("certifications.{$cert->id}"))
                        <small class="info-danger">{{ $errors->details->first("certifications.{$cert->id}") }}</small>
                        @endif
                    </div>
                    <div class="js-cert-file-{{ $cert->id }}" {!! in_array($cert->id, Arr::wrap(old('certifications', $model->certifications->contains($cert->id) ? $cert->id : null))) ? '' : 'style="display: none;"'
                        !!}>
                        {!! BladeHelper::uploaderField($model, "certifications_{$cert->id}", ['extensions' => 'pdf', 'allowRestore' => $signoffForm ?? false]) !!}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
