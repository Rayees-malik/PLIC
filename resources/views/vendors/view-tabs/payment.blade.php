<div id="payment-view">
    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <p>Who to MCB</p>
                <h4>{{ $model->who_to_mcb }}</h4>
            </div>
        </div>
        <div class="col-md-4">
            <div class="info-box">
                <p>FOB Purity Distribution Center</p>
                <h4>{{ $model->fob_purity_distribution_centres ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="info-box">
                <p>Cheque Payable To</p>
                <h4>{{ $model->cheque_payable_to }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="info-box">
                <p>Payment Terms</p>
                <h4>{{ $model->payment_terms }}</h4>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="info-box">
                <p>Consignment</p>
                <h4>{{ $model->consignment ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>

    @if($model->special_shipping_requirements)
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Special Shipping Requirements</p>
                <h4>{{ $model->special_shipping_requirements }}</h4>
            </div>
        </div>
    </div>
    @endif

    @if($model->return_policy)
    <div class="row">
        <div class="col-12">
            <div class=" info-box">
                <p>Return Policy</p>
                <h4>{{ $model->return_policy }}</h4>
            </div>
        </div>
    </div>
    @endif
</div>
