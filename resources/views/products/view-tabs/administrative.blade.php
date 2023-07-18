<div id="administrative-view">
    <div class="row">
        <div class="col-xl-4">
            <div class="info-box">
                <p>Hide from Catalogue</p>
                <h4>{{ optional($model->as400StockData)->hide_from_catalogue ? 'Yes' : 'No' }}</h4>
            </div>
        </div>
    </div>
</div>
