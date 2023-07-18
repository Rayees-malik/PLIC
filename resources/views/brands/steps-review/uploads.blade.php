<h3 class="js-review-toggle review-toggle {{ $errors->uploads->count() ? 'open error' : '' }}">Uploads</h3>
<div class="review-wrap">
    <div class="review-content {{ $errors->uploads->count() ? 'error' : '' }}">
        <div class="row">
            <div class="col-xl-6">
                <h4>Main Logo</h4>
                @include('partials.review.media-field', ['collection' => 'logo'])
            </div>
            <div class="col-xl-6">
                <h4>Alternative Logos</h4>
                @include('partials.review.media-field', ['collection' => 'alternative_logo'])
            </div>
        </div>
        <div class="row">
            <div class="col-xl-6">
                <h4>Food Administration License</h4>
                @include('partials.review.media-field', ['collection' => 'food_administration_license'])
            </div>
            <div class="col-xl-6">
                <h4>Third Party Facility Certification</h4>
                @include('partials.review.media-field', ['collection' => 'facility_certification'])
            </div>
        </div>
    </div>
</div>
