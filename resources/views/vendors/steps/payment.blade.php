<div id="payment" class="js-stepper-step stepper-step">
    <h3 class="form-section-title">Payment</h3>
    <div class="row mb-4">
        <div class="input-wrap col-xl-4 {{ $errors->payment->has("who_to_mcb") ? 'input-danger' : '' }}">
            <label>Who to MCB
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="who_to_mcb" value="{{ old('who_to_mcb', $model->who_to_mcb) }}">
                </div>
            </label>
            @if($errors->payment->has('who_to_mcb'))
            <small class="info-danger">{{ $errors->payment->first('who_to_mcb') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->payment->has("cheque_payable_to") ? 'input-danger' : '' }}">
            <label>Cheque Payable To
                <div class="icon-input">
                    <i class="material-icons pre-icon">money</i>
                    <input type="text" name="cheque_payable_to" value="{{ old('cheque_payable_to', $model->cheque_payable_to) }}">
                </div>
            </label>
            @if($errors->payment->has('cheque_payable_to'))
            <small class="info-danger">{{ $errors->payment->first('cheque_payable_to') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->payment->has("payment_terms") ? 'input-danger' : '' }}">
            <label>Payment Terms
                <div class="icon-input">
                    <i class="material-icons pre-icon">attach_money</i>
                    <input type="text" name="payment_terms" value="{{ old('payment_terms', $model->payment_terms) }}">
                </div>
                <small>As per distribution agreement</small>
            </label>
            @if($errors->payment->has('payment_terms'))
            <small class="info-danger">{{ $errors->payment->first('payment_terms') }}</small>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 offset-xl-8">
            <div class="checkbox-wrap">
                <label class="checkbox">
                    <input type="hidden" class="no-history" name="consignment" value="0">
                    <input type="checkbox" name="consignment" value="1" {{ old('consignment', $model->consignment) ? 'checked' : '' }}>
                    <span class="checkbox-checkmark"></span>
                    <span class="checkbox-label">Consignment</span>
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="input-wrap col-12 {{ $errors->payment->has("special_shipping_requirements") ? 'input-danger' : '' }}">
            <label>Special Shipping Requirements
                <textarea type="text" name="special_shipping_requirements" value="{{ old('special_shipping_requirements', $model->special_shipping_requirements) }}">{{
                    old('special_shipping_requirements', $model->special_shipping_requirements)
                }}</textarea>
                @if($errors->payment->has('special_shipping_requirements'))
                <small class="info-danger">{{ $errors->payment->first('special_shipping_requirements') }}</small>
                @endif
            </label>
        </div>
    </div>

    <h3 class="form-section-title">Ordering</h3>
    <div class="row">
        <div class="input-wrap col-12 {{ $errors->payment->has("return_policy") ? 'input-danger' : '' }}">
            <label>Return Policy
                <textarea type="text" name="return_policy" value="{{ old('return_policy', $model->return_policy) }}">{{ $model->return_policy }}</textarea>
            </label>
            <small>As per distribution agreement</small>
            @if($errors->payment->has('return_policy'))
            <small class="info-danger">{{ $errors->payment->first('return_policy') }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->payment->has("fob_purity_distribution_centres") ? 'input-danger' : '' }}">
            <label>FOB Purity Distribution Centres</label>
            <div class="inline-radio-group">
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="fob_purity_distribution_centres" value="1" {{ old('fob_purity_distribution_centres', $model->fob_purity_distribution_centres) !== '0' ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">Yes</span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        <input type="radio" name="fob_purity_distribution_centres" value="0" {{ old('fob_purity_distribution_centres', $model->fob_purity_distribution_centres) == '0' ? 'checked' : '' }}>
                        <span class="radio-checkmark"></span>
                        <span class="radio-label">No</span>
                    </label>
                </div>
            </div>
            @if($errors->payment->has('fob_purity_distribution_centres'))
            <small class="info-danger">{{ $errors->payment->first('fob_purity_distribution_centres') }}</small>
            @endif
        </div>
    </div>
</div>
