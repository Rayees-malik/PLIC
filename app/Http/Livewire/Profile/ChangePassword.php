<?php

namespace App\Http\Livewire\Profile;

use Livewire\Component;

class ChangePassword extends Component
{
    public $password;

    public $password_confirmation;

    protected $rules = [
        'password' => [
            'required',
            'min:8',
            // 'regex:/^(?=.{8,})((?=.*\d)(?=.*[a-z])(?=.*[A-Z])|(?=.*\d)(?=.*[a-zA-Z])(?=.*[\W_])|(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_])).*$/',
            'confirmed',
        ],
    ];

    public function save()
    {
        $this->validate();
        $user = auth()->user();

        $user->password = bcrypt($this->password);
        $user->save();

        flash('Password updated successfully.', 'success');
        $this->dispatchBrowserEvent('notify', ['content' => 'Password updated successfully!', 'type' => 'success']);

        return redirect()->route('home');
    }

    public function render()
    {
        return view('livewire.profile.change-password');
    }
}
