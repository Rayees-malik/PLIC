@foreach ($model->contactRoles as $key => $role)
    <div class="js-contact-role-{{ $key }}">
        <h4>{{ $role['display'] }} Contact{{ $role['multiple'] ? 's' : '' }}</h4>
            @forelse ($model->contactsByRoleAsLoaded($key) as $contact)
                @include('partials.contacts.contact-form', [
                    'key' => $key,
                    'index' => $loop->iteration,
                    'contact' => $contact
                ])
            @empty
                @forelse (Arr::wrap(old('contact-role')) as $oldKey => $oldRole)
                    @if ($oldRole == $key)
                        @include('partials.contacts.contact-form', [
                            'key' => $oldKey,
                            'index' => '',
                            'contact' => null,
                            'roleOverride' => $key
                        ])
                    @endif
                @empty
                    @include('partials.contacts.contact-form', [
                        'key' => $key,
                        'index' => 0,
                        'contact' => null
                    ])
                @endforelse
            @endforelse
    </div>
    @if ($role['multiple'])
        @include('partials.contacts.add-contact', ['key' => $key])
    @endif
@endforeach
<div class="d-none">
    @include('partials.contacts.contact-form', [
        'key' => 'new',
        'index' => '',
        'contact' => null,
        'disabled' => 'disabled'
    ])
</div>
