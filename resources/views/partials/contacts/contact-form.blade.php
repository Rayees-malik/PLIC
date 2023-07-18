<div class="container pb-3 js-contact-wrap-{{ $key }} {{ $contact && $contact->isTrashed ? 'pending-delete' : '' }}">
    <input type="hidden" name="contact-id[{{ $key . $index }}]" value="{{ optional($contact)->id }}" {{ $disabled ?? '' }} />
    <input type="hidden" name="contact-role[{{ $key . $index }}]" value="{{ $roleOverride ?? $key }}" {{ $disabled ?? '' }} />
    <input type="hidden" name="contact-deleted[{{ $key . $index }}]" value="{{ $contact ? $contact->isTrashed : 0 }}" {{ $disabled ?? '' }} />
    <div class="row">
        <div class="input-wrap col-xl-4 {{ $errors->has("contact-name.{$key}{$index}") ? 'input-danger' : '' }}">
            <label>Name
                <div class="icon-input">
                    <i class="material-icons pre-icon">perm_identity</i>
                    <input type="text" name="contact-name[{{ $key . $index }}]" value="{{ $contact ? $contact->name : old("contact-name.{$key}{$index}") }}" {{ $disabled ?? '' }} {{ optional($contact)->isTrashed ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->has("contact-name.{$key}{$index}"))
            <small class="info-danger">{{ $errors->first("contact-name.{$key}{$index}") }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->has("contact-email.{$key}{$index}") ? 'input-danger' : '' }}">
            <label>E-Mail Address
                <div class="icon-input">
                    <i class="material-icons pre-icon">email</i>
                    <input type="text" name="contact-email[{{ $key . $index }}]" value="{{ $contact ? $contact->email : old("contact-email.{$key}{$index}") }}" {{ $disabled ?? '' }} {{ optional($contact)->isTrashed ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->has("contact-email.{$key}{$index}"))
            <small class="info-danger">{{ $errors->first("contact-email.{$key}{$index}") }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-4 {{ $errors->has("contact-phone.{$key}{$index}") ? 'input-danger' : '' }}">
            <label>Phone Number
                <div class="icon-input">
                    <i class="material-icons pre-icon">phone</i>
                    <input type="text" name="contact-phone[{{ $key . $index }}]" value="{{ $contact ? $contact->phone : old("contact-phone.{$key}{$index}") }}" {{ $disabled ?? '' }} {{ optional($contact)->isTrashed ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->has("contact-phone.{$key}{$index}"))
            <small class="info-danger">{{ $errors->first("contact-phone.{$key}{$index}") }}</small>
            @endif
        </div>

        <div class="input-wrap col-xl-8 {{ $errors->has("contact-position.{$key}{$index}") ? 'input-danger' : '' }}">
            <label>Position
                <div class="icon-input">
                    <i class="material-icons pre-icon">assignment_ind</i>
                    <input type="text" name="contact-position[{{ $key . $index }}]" value="{{ $contact ? $contact->position : old("contact-position.{$key}{$index}") }}" {{ $disabled ?? '' }} {{ optional($contact)->isTrashed ? 'readonly' : '' }}>
                </div>
            </label>
            @if ($errors->has("contact-position.{$key}{$index}"))
            <small class="info-danger">{{ $errors->first("contact-position.{$key}{$index}") }}</small>
            @endif
        </div>

        <div class="col-xl-4 contact-restore-history-wrap">
            <button type="button" class="link-btn js-delete-contact {{ !$contact || !$contact->isTrashed ? 'delete' : '' }}" title="Delete Contact" data-role="{{ $key }}" data-index="{{ $key . $index }}">
                @if ($contact && $contact->isTrashed)
                <i class="material-icons">restore_from_trash</i>
                Restore
                @else
                <i class="material-icons">delete_forever</i>
                Delete
                @endif
            </button>
        </div>
    </div>
</div>
