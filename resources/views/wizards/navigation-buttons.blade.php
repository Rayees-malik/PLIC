<div class="bottom-controls">
  <div class="js-prev js-stepper-button link-btn" title="Previous Step"
  style="opacity: 0.4;"
   wire:click="previousStep">
      <i class="material-icons">arrow_back</i>
      Back
  </div>

  <div class="js-next js-stepper-button primary-btn" title="Next Step" data-dir="next" wire:click="submit">
      Next
      <i class="material-icons">arrow_forward</i>
  </div>
</div>
