<div id="contacts" class="js-stepper-step stepper-step">
    <div id="contact-wrap">
        <h3 class="form-section-title">Contacts</h3>
        <div class="mb-3">
            <em>Only contacts that differ from the contacts submitted for the vendor are required.</em>
        </div>
        @include('partials.contacts.contacts', ['errors' => $errors->contacts])
    </div>
</div>
