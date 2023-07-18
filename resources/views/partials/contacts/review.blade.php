<h3 class="js-review-toggle review-toggle {{ optional($errors->contacts)->count() || $errors->missing_contacts->count() ? 'open error' : '' }}">Contacts</h3>
<div class="review-wrap">
    <div class="review-content {{ optional($errors->contacts)->count() || $errors->missing_contacts->count() ? 'error' : '' }}">
        @foreach ($model->contactRoles as $key => $role)
        <div class="contact-review-wrap">
            <h3>{{ $role['display'] }} {{ Str::plural('Contact', $model->contactsByRole($key)->count()) }}</h3>
            @if ($errors->missing_contacts->has("missing-{$key}"))
            <div class="error-message">{{ $errors->missing_contacts->first("missing-{$key}") }}</div>
            @endif

            @forelse ($model->contactsByRole($key) as $contact)
            <div class="row">
                <div class="col-xl-4">
                    <h4>Name</h4>
                    @include('partials.review.field', ['field' => 'name', 'model' => $contact, 'formField' => "contact-name.{$key}{$loop->iteration}", 'key' => 'contacts'])
                </div>
                <div class="col-xl-4">
                    <h4>Position</h4>
                    @include('partials.review.field', ['field' => 'position', 'model' => $contact, 'formField' => "contact-position.{$key}{$loop->iteration}", 'key' => 'contacts'])
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <h4>E-Mail Address</h4>
                    @include('partials.review.field', ['field' => 'email', 'model' => $contact, 'formField' => "contact-email.{$key}{$loop->iteration}", 'key' => 'contacts'])
                </div>
                <div class="col-xl-4">
                    <h4>Phone Number</h4>
                    @include('partials.review.field', ['field' => 'phone', 'model' => $contact, 'formField' => "contact-phone.{$key}{$loop->iteration}", 'key' => 'contacts'])
                </div>
            </div>
            @empty
            -
            @endforelse
        </div>
        @endforeach
    </div>
</div>
