<div id="contacts-view">
    <div class="row">
        @foreach ($model->contacts->groupBy('role') as $role => $contacts)
        <div class="col-md-4 mb-3">
            <h3 class="form-section-title">{{ ucfirst($role) }} {{ Str::plural('Contact', $model->contactsByRole($role)->count()) }}</h3>
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
