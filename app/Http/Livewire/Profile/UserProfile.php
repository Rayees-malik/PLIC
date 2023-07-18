<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;

class UserProfile extends Component
{
    use WithFileUploads;

    public $subscriptions = [];

    public $subscriptionOptions = [];

    public $newSignature;

    public $signature;

    protected $rules = [
        'name' => ['required'],
        'email' => ['required', 'email'],
        'subscriptions' => ['array'],
        'subscriptions.*' => ['string'],
    ];

    public function save()
    {
        $this->validate();

        $user = auth()->user();

        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->newSignature) {
            $user->addMedia($this->newSignature)
                ->toMediaCollection('signature');

            $this->newSignature = null;
        }

        $unsubscriptions = collect($this->subscriptionOptions)
            ->keys()
            ->diff($this->subscriptions)
            ->values();

        $user->unsubscriptions = $unsubscriptions;
        $user->save();

        flash('Profile updated successfully.', 'success');
        $this->dispatchBrowserEvent('notify', ['content' => 'Profile updated successfully!', 'type' => 'success']);
    }

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->signature = $user?->getSignature();

        $this->subscriptionOptions = config('notifications.subscriptions');

        $this->subscriptions = collect($this->subscriptionOptions)
            ->keys()
            ->diff($user->unsubscriptions)
            ->values();
    }

    public function render()
    {
        return view('livewire.profile.user-profile');
    }
}
