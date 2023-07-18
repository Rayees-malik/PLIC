<div id="french-view">
    <div class="row">
        <div class="col-4">
            <div class="info-box">
                <p>Name</p>
                <h4>{{ $model->name_fr ?? '-' }}</h4>
            </div>
        </div>
        <div class="col-8">
            <div class="info-box">
                <p>Catalogue Notice</p>
                <h4>{{ $model->catalogue_notice_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Brand Description</p>
                <h4>{{ $model->description_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <p>Unpublished New Listing Deal</p>
                <h4>{{ $model->unpublished_new_listing_deal_fr ?? '-' }}</h4>
            </div>
        </div>
    </div>
</div>
