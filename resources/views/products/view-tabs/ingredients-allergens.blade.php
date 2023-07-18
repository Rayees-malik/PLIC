<div id="ingredients-allergens-view">
    <h3 class="form-section-title">Ingredients</h3>
    <div class="row">
        <div class="col-12">
            <div class="info-box">
                <h4>{{ $model->ingredients }}</h4>
            </div>
        </div>
    </div>

    <h3 class="form-section-title">Allergens</h3>
    <div class="row">
        <div class="col">
            <div class="allergens-wrap mb-2">
                <div></div>
                <div><b>Contains</b></div>
                <div><b>May Contain</b></div>
                <div><b>Does Not Contain</b></div>
            </div>
            @foreach ($model->allergens as $allergen)
            <div class="allergens-wrap">
                <div>{{ $allergen->name }}</div>
                <div class="radio-wrap">
                    <label class="radio">
                        @if ($allergen->pivot->contains == 1)
                        <input type="radio" checked disabled>
                        @endif
                        <span class="radio-checkmark"></span>
                        <span class="radio-label"></span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        @if ($allergen->pivot->contains == 0)
                        <input type="radio" checked disabled>
                        @endif
                        <span class="radio-checkmark"></span>
                        <span class="radio-label"></span>
                    </label>
                </div>
                <div class="radio-wrap">
                    <label class="radio">
                        @if ($allergen->pivot->contains == -1)
                        <input type="radio" checked disabled>
                        @endif
                        <span class="radio-checkmark"></span>
                        <span class="radio-label"></span>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <h3 class="form-section-title">Certifications</h3>
    @foreach ($certifications as $id => $certification)
    <div class="row">
      <div class="col">
        <div class="d-flex">
          @if ($model->certifications->contains($id))
            <span class="material-icons checked-circle">check_circle</span>
          @else
            <span class="material-icons empty-circle">radio_button_unchecked</span>
          @endif
          @if ($model->getMedia("certifications_{$id}")->first())
            <a class="ml-2" href="{{ $model->getMedia("certifications_{$id}")->first()->getFullUrl() }}" target="_blank">{{ $certification }}</a>
          @else
            <span class="ml-2">{{ $certification }}</span>
          @endif
        </div>
      </div>
    </div>
  @endforeach
</div>
