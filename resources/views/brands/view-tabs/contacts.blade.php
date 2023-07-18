<div id="contacts-view">
    @if ($model->contacts->count())
    <h3 class="form-section-title">Brand Contacts</h3>
    <div class="row">
        @foreach ($model->contacts->groupBy('role') as $role => $contacts)
        <div class="col-md-4 mb-3">
            <h4 class="form-section-title">{{ ucfirst($role) }} {{ Str::plural('Contact', $model->contactsByRole($role)->count()) }}</h4>
            @foreach ($contacts as $contact)
            <div class="mb-3">
                <strong>{{ $contact->name }}</strong><br>
                {{ $contact->position }}<br>
                {{ $contact->email }}<br>
                {{ $contact->phone }}
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @endif

    <h3 class="form-section-title">Vendor Contacts</h3>
    <div class="row">
        @foreach ($model->vendor->contacts->groupBy('role') as $role => $contacts)
        <div class="col-md-4 mb-3">
            <h4 class="form-section-title">{{ ucfirst($role) }} {{ Str::plural('Contact', $model->vendor->contactsByRole($role)->count()) }}</h4>
            @foreach ($contacts as $contact)
            <div class="mb-3">
                <strong>{{ $contact->name }}</strong><br>
                {{ $contact->position }}<br>
                {{ $contact->email }}<br>
                {{ $contact->phone }}
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>
