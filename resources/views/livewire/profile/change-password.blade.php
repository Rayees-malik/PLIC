<div>
  <form wire:submit.prevent="save">
    <div class="formContainer">
      <div class="input-wrap {{ $errors->has('password') ? ' input-danger' : '' }}">
        <label>Change Password
          <div class="icon-input">
            <i class="material-icons pre-icon">vpn_key</i>
            <input type="password" wire:model="password">
          </div>
        </label>
        @error('password')
        <small class="info-danger">{{ $message }}</small>
        @enderror
      </div>

      <div class="input-wrap {{ $errors->has('passwordConfirmation') ? ' input-danger' : '' }}">
        <label>Confirm Password
          <div class="icon-input">
            <i class="material-icons pre-icon">vpn_key</i>
            <input type="password" wire:model="password_confirmation">
          </div>
        </label>
        @error('password_confirmation')
        <small class="info-danger">{{ $message }}</small>
        @enderror
      </div>
    </div>

    <div class="bottom-controls">
      <a class="secondary-btn block-btn" href="{{ BladeHelper::backOr(route('home')) }}" title="Cancel">
        <i class="material-icons">cancel</i>
        Cancel
      </a>
      <button type="submit" class="primary-btn block-btn" title="Save changes">
        <i class="material-icons">save</i>
        Update
      </button>
    </div>
  </form>
</div>
