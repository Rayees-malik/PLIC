<?php

namespace App\Traits;

use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

trait HasContacts
{
    public function getContactRolesAttribute()
    {
        return [
            'contact' => [
                'display' => 'Contacts',
                'multiple' => false,
                'required' => 0,
            ],
        ];
    }

    public function contactsByRole($role)
    {
        if (! isset($this->relations['contacts'])) {
            $this->load('contacts');
        }

        return $this->contacts->where('role', $role)->whereNull(
            $this->getDeletedAtColumn()
        );
    }

    public function contactsByRoleWithTrashed($role)
    {
        if (! isset($this->relations['contacts'])) {
            $this->load(['contacts' => function ($query) {
                $query->withTrashed();
            }]);
        }

        return $this->contacts->where('role', $role);
    }

    public function contactsByRoleAsLoaded($role)
    {
        return $this->contacts->where('role', $role);
    }

    public function contacts()
    {
        return $this->morphMany(App\Models\Contact::class, 'contactable');
    }

    public function missingContacts()
    {
        $missingContacts = [];

        foreach ($this->contactRoles as $key => $role) {
            if ($this->contactsByRole($key)->count() < $role['required']) {
                $missingContacts[$key] = [
                    'display' => $role['display'],
                    'required' => $role['required'],
                    'present' => $this->contactsByRole($key)->count(),
                ];
            }
        }

        return $missingContacts;
    }

    public function missingContactErrors()
    {
        $errors = new MessageBag;
        $missingContacts = $this->missingContacts();

        foreach ($missingContacts as $key => $contact) {
            $contactPlural = Str::plural('contact', $contact['required']);
            $isArePlural = Str::plural('is', $contact['required']);
            $errors->add("missing-{$key}", "At least {$contact['required']} {$contact['display']} {$contactPlural} {$isArePlural} required.");
        }

        return $errors;
    }
}
