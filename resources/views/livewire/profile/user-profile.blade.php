<div>
  <form wire:submit.prevent="save">
    <x-ajax-loader wire:loading.class="ajax-loader" />
    <div class="formContainer">
      <div class="input-wrap @error('name') input-danger @enderror">
        <label>Name
          <div class="icon-input">
            <i class="material-icons pre-icon">perm_identity</i>
            <input type="text" wire:model.defer="name">
          </div>
        </label>
        @error('name')<small class=" info-danger">{{ $message }}</small>@enderror
      </div>

      <div class="input-wrap @error('email')input-danger @enderror">
        <label>Email
          <div class="icon-input">
            <i class="material-icons pre-icon">email</i>
            <input type="text" wire:model.defer="email">
          </div>
        </label>
        @error('email')<small class="info-danger">{{ $message }}</small>@enderror
      </div>

      <x-input.label >Signature</x-input.label >
      <div class="tw-flex tw-items-center">
        <span class="tw-h-12 tw-w-12 tw-overflow-hidden tw-bg-gray-100">
            @if ($newSignature)
                <img src="{{ $newSignature->temporaryUrl() }}" alt="Profile Photo">
            @else
                <img src="{{ auth()->user()->getSignature()?->getUrl() }}" alt="Profile Photo">
            @endif
        </span>

        <span class="tw-ml-5 tw-rounded-md tw-shadow-sm">
            <input type="file" wire:model="newSignature">
        </span>
    </div>

      <div x-data="{
        subscriptions: @entangle('subscriptions').defer,
        selectAll: false,
        toggleValue(subscription) {
          if(this.subscriptions.includes(subscription)) {
            this.subscriptions = this.subscriptions.filter(function(value, index, arr) {
              return value !== subscription;
            });
          } else {
            this.subscriptions.push(subscription);
          }

          this.subscriptions = [... new Set(this.subscriptions)];

          this.selectAll = $refs.checkboxes.querySelectorAll('input:checked').length === $refs.checkboxes.querySelectorAll('input').length;
        },
        toggleSelectAll() {
          this.selectAll = !this.selectAll;

          $refs.checkboxes.querySelectorAll('input').forEach(el => {
            el.checked = this.selectAll;
            if(this.selectAll) {
              this.subscriptions.push(el.value);
              this.subscriptions = [... new Set(this.subscriptions)];
            } else {
              this.subscriptions = [];
            }
          });
    },
  }", x-init="$refs.checkboxes.querySelectorAll('input:checked').length === $refs.checkboxes.querySelectorAll('input').length
  ? $data.selectAll = true
  : $data.selectAll = false">
        <h4 class="mt-3">Email Subscriptions</h4>
        <div class="tw-flex tw-flex-col">
          <div class="tw-flex mb-3">
            <x-input.checkbox label="Select All" value="" x-model="selectAll" @click="toggleSelectAll()" />
          </div>
          <div x-ref="checkboxes">
            @foreach ($subscriptionOptions as $key => $value)
              <x-input.checkbox @click="selectAll ? selectAll=false : ''; toggleValue($el.value)" wire:model.defer="subscriptions" value="{{ $key }}" label="{{ $value }}" />
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="bottom-controls">
      <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('home')) }}" title="Cancel">
        <i class="material-icons">cancel</i>
        Cancel
      </a>
      <button type="submit"class="primary-btn block-btn" title="Save changes">
        <i class="material-icons">save</i>
        Update
      </button>
    </div>
  </form>
</div>
