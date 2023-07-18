<div class="form-stepper">
  <div class="stepper-dot-wrap">
    @foreach ($steps as $step)
    <h class="js-stepper-dot stepper-dot {{ $step->isCurrent() ? 'active' : '' }}"
                      wire:click="showStep('{{ $step->stepName }}')">
              <h5 class="title text-xl">{{ $step->label }}</h5>
        <div class="checkpoint"></div>
    </h>
    @endforeach
  </div>
</div>
