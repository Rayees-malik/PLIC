<div id="administrative" class="js-stepper-step stepper-step" data-hidden="{{ Arr::get($step, 'hidden', false) }}">
    @if (auth()->user()->can('edit', App\Models\Brand::class) && !auth()->user()->isVendor)
    <h3 class="form-section-title">Administrative</h3>
    <div class="row">
        <div class="dropdown-wrap col-xl-4 {{ $errors->administrative->has('status') ? 'dropdown-danger' : '' }}">
            <label>Status</label>
            <h4>{{ App\Helpers\StatusHelper::toString($model->status) }}</h4>
            @if ($errors->administrative->has('status'))
            <small class="info-danger">{{ $errors->administrative->first('status') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->administrative->has('category_code') ? 'input-danger' : '' }}">
            <label>AS400 Category Code
                <input type="text" name="category_code" value="{{ old('category_code', $model->category_code) }}">
            </label>
            @if ($errors->brand->has('category_code'))
            <small class="info-danger">{{ $errors->brand->first('category_code') }}</small>
            @endif
        </div>
        <div class="dropdown-wrap col-xl-4 {{ $errors->brand->has('as400_category') ? 'dropdown-danger' : '' }}">
            <label>AS400 Category</label>
            <div class="dropdown-icon">
                <select name="as400_category">
                    @if (!old('as400_category', $model->as400_category))
                    <option value disabled selected>Select Category</option>
                    @endif
                    @foreach (Config::get('as400-categories') as $category)
                    <option value="{{ $category }}" {{ old('as400_category', $model->as400_category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            @if ($errors->brand->has('currency_id'))
            <small class="info-danger">{{ $errors->brand->first('currency_id') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->administrative->has('education_portal') ? 'input-danger' : '' }}">
            <label>Education Portal</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="education_portal" value="0" {{ !old('education_portal', $model->education_portal) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="education_portal" value="1" {{ old('education_portal', $model->education_portal) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->administrative->has('education_portal'))
            <small class="info-danger">{{ $errors->administrative->first('education_portal') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->administrative->has('hide_from_exports') ? 'input-danger' : '' }}">
            <label>Hide From Exports</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="hide_from_exports" value="0" {{ !old('hide_from_exports', $model->hide_from_exports) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="hide_from_exports" value="1" {{ old('hide_from_exports', $model->hide_from_exports) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->administrative->has('hide_from_exports'))
            <small class="info-danger">{{ $errors->administrative->first('hide_from_exports') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4 {{ $errors->brand->has('finance_brand_number') ? 'input-danger' : '' }}">
            <label>Finance Brand Number Override
                <div class="icon-input">
                    <i class="material-icons pre-icon">looks_one</i>
                    <input type="text" name="finance_brand_number" autocomplete="off" value="{{ old('finance_brand_number', $model->catalogue_notice_fr) }}">
                </div>
            </label>
            @if ($errors->brand->has('finance_brand_number'))
            <small class="info-danger">{{ $errors->brand->first('finance_brand_number') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-8 {{ $errors->administrative->has('catalogue_notice') ? 'input-danger' : '' }}">
            <label>Catalogue Notice
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="catalogue_notice" autocomplete="off" value="{{ old('catalogue_notice', $model->catalogue_notice) }}">
                </div>
            </label>
            @if ($errors->brand->has('catalogue_notice'))
            <small class="info-danger">{{ $errors->brand->first('catalogue_notice') }}</small>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="input-wrap col-xl-8 {{ $errors->brand->has('catalogue_notice_fr') ? 'input-danger' : '' }}">
            <label>Catalogue Notice (FR)
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="catalogue_notice_fr" autocomplete="off" value="{{ old('catalogue_notice_fr', $model->catalogue_notice_fr) }}">
                </div>
            </label>
            @if ($errors->brand->has('catalogue_notice_fr'))
            <small class="info-danger">{{ $errors->brand->first('catalogue_notice_fr') }}</small>
            @endif
        </div>
    </div>
    <h3 class="form-section-title">MCB Approval</h3>
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->mcb->has('nutrition_house') ? 'input-danger' : '' }}">
            <label>Nutrition House</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="nutrition_house" class="js-nutrition-house" value="0" {{ !old('nutrition_house', $model->nutrition_house) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="nutrition_house" class="js-nutrition-house" value="1" {{ old('nutrition_house', $model->nutrition_house) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
            @if ($errors->mcb->has('nutrition_house'))
            <small class="info-danger">{{ $errors->mcb->first('nutrition_house') }}</small>
            @endif
        </div>
    </div>
    <div class="js-nutrition-house-wrapper" {!! old('nutrition_house', $model->nutrition_house) ? '' : 'style="display: none;"' !!}>
        <div class="row">
            <div class="input-wrap col-xl-4 js-nutrition-house {{ $errors->mcb->has('nutrition_house_payment_type') ? 'input-danger' : '' }}">
                <label>Nutrition House Payment Type</label>
                <div class="inline-radio-group">
                    <div class="radio-wrap">
                        <label class="radio">
                            <input type="radio" name="nutrition_house_payment_type" value="vendor" {{ old('nutrition_house_payment_type', $model->nutrition_house_payment_type) != 'purity' ? 'checked' : '' }}>
                            <span class="radio-checkmark"></span>
                            <span class="radio-label">Vendor</span>
                        </label>
                    </div>
                    <div class="radio-wrap">
                        <label class="radio">
                            <input type="radio" name="nutrition_house_payment_type" value="purity" {{ old('nutrition_house_payment_type', $model->nutrition_house_payment_type) == 'purity' ? 'checked' : '' }}>
                            <span class="radio-checkmark"></span>
                            <span class="radio-label">Purity</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="input-wrap col-xl-4 {{ $errors->mcb->has('nutrition_house_payment') ? 'input-danger' : '' }}">
                <label>Nutrition House Payment
                    <div class="icon-input">
                        <i class="material-icons pre-icon">money</i>
                        <input type="text" name="nutrition_house_payment" value="{{ old('nutrition_house_payment', $model->nutrition_house_payment) ?? '7' }}">
                    </div>
                </label>
                @if ($errors->mcb->has('nutrition_house_payment'))
                <small class="info-danger">{{ $errors->mcb->first('nutrition_house_payment') }}</small>
                @endif
            </div>
        </div>
        <div class="row js-nutrition-house-split" {!! old('nutrition_house_payment_type', $model->nutrition_house_payment_type) == 'purity' ? '' : 'style="display: none;"' !!}>
            <div class="input-wrap col-xl-4 {{ $errors->mcb->has('nutrition_house_percentage') ? 'input-danger' : '' }}">
                <label>Nutrition House Percentage
                    <div class="icon-input">
                        <i class="material-icons pre-icon">pie_chart</i>
                        <input type="text" name="nutrition_house_percentage" value="{{ old('nutrition_house_percentage', $model->nutrition_house_percentage) }}">
                    </div>
                </label>
                @if ($errors->mcb->has('nutrition_house_percentage'))
                <small class="info-danger">{{ $errors->mcb->first('nutrition_house_percentage') }}</small>
                @endif
            </div>
            <div class="input-wrap col-xl-4 {{ $errors->mcb->has('nutrition_house_purity_percentage') ? 'input-danger' : '' }}">
                <label>Purity Life Percentage
                    <div class="icon-input">
                        <i class="material-icons pre-icon">pie_chart</i>
                        <input type="text" name="nutrition_house_purity_percentage" value="{{ old('nutrition_house_purity_percentage', $model->nutrition_house_purity_percentage) }}">
                    </div>
                </label>
                @if ($errors->mcb->has('nutrition_house_purity_percentage'))
                <small class="info-danger">{{ $errors->mcb->first('nutrition_house_purity_percentage') }}</small>
                @endif
            </div>
        </div>
    </div>
    <hr />
    <div class="row pt-3">
        <div class="input-wrap col-xl-4">
            <label>Health First</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="health_first" class="js-health-first" value="0" {{ !old('health_first', $model->health_first) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="health_first" class="js-health-first" value="1" {{ old('health_first', $model->health_first) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="js-health-first-wrapper" {!! old('health_first', $model->health_first) ? '' : 'style="display: none;"' !!}>
        <div class="row">
            <div class="input-wrap col-xl-4 js-health-first {{ $errors->mcb->has('health_first_payment_type') ? 'input-danger' : '' }}">
                <label>Health First Payment Type</label>
                <div class="inline-radio-group">
                    <div class="radio-wrap">
                        <label class="radio">
                            <input type="radio" name="health_first_payment_type" value="vendor" {{ old('health_first_payment_type', $model->health_first_payment_type) != 'purity' ? 'checked' : '' }}>
                            <span class="radio-checkmark"></span>
                            <span class="radio-label">Vendor</span>
                        </label>
                    </div>
                    <div class="radio-wrap">
                        <label class="radio">
                            <input type="radio" name="health_first_payment_type" value="purity" {{ old('health_first_payment_type', $model->health_first_payment_type) == 'purity' ? 'checked' : '' }}>
                            <span class="radio-checkmark"></span>
                            <span class="radio-label">Purity</span>
                        </label>
                    </div>
                </div>
                @if ($errors->mcb->has('health_first_payment_type'))
                <small class="info-danger">{{ $errors->mcb->first('health_first_payment_type') }}</small>
                @endif
            </div>
            <div class="input-wrap col-xl-4 {{ $errors->mcb->has('health_first_payment') ? 'input-danger' : '' }}">
                <label>Health First Payment
                    <div class="icon-input">
                        <i class="material-icons pre-icon">money</i>
                        <input type="text" name="health_first_payment" value="{{ old('health_first_payment', $model->health_first_payment) ?? '3' }}">
                    </div>
                </label>
                @if ($errors->mcb->has('health_first_payment'))
                <small class="info-danger">{{ $errors->mcb->first('health_first_payment') }}</small>
                @endif
            </div>
        </div>
        <div class="row js-health-first-split" {!! old('health_first_payment_type', $model->health_first_payment_type) == 'purity' ? '' : 'style="display: none;"' !!}>
            <div class="input-wrap col-xl-4 {{ $errors->mcb->has('health_first_percentage') ? 'input-danger' : '' }}">
                <label>Health First Percentage
                    <div class="icon-input">
                        <i class="material-icons pre-icon">pie_chart</i>
                        <input type="text" name="health_first_percentage" value="{{ old('health_first_percentage', $model->health_first_percentage) }}">
                    </div>
                </label>
                @if ($errors->mcb->has('health_first_percentage'))
                <small class="info-danger">{{ $errors->mcb->first('health_first_percentage') }}</small>
                @endif
            </div>
            <div class="input-wrap col-xl-4 {{ $errors->mcb->has('health_first_purity_percentage') ? 'input-danger' : '' }}">
                <label>Purity Life Percentage
                    <div class="icon-input">
                        <i class="material-icons pre-icon">pie_chart</i>
                        <input type="text" name="health_first_purity_percentage" value="{{ old('health_first_purity_percentage', $model->health_first_purity_percentage) }}">
                    </div>
                </label>
                @if ($errors->mcb->has('health_first_purity_percentage'))
                <small class="info-danger">{{ $errors->mcb->first('health_first_purity_percentage') }}</small>
                @endif
            </div>
        </div>
    </div>
    <hr />
    <div class="row pt-3">
        <div class="input-wrap col-xl-4 {{ $errors->mcb->has('default_pl_discount') ? 'input-danger' : '' }}">
            <label>Additional Purity Promo Discount
                <div class="icon-input">
                    <i class="pre-icon">%</i>
                    <input type="text" name="default_pl_discount" value="{{ old('default_pl_discount', $model->default_pl_discount) }}">
                </div>
            </label>
            @if ($errors->mcb->has('default_pl_discount'))
            <small class="info-danger">{{ $errors->mcb->first('default_pl_discount') }}</small>
            @endif
        </div>
        <div class="input-wrap col-xl-4">
            <label>Allow OI Promos</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allow_oi" value="0" {{ !old('allow_oi', $model->allow_oi) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="allow_oi" value="1" {{ old('allow_oi', $model->allow_oi) ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
