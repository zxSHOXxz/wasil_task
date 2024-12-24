<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditUserEmailModal extends Component
{
    public $user;
    public $email;
    public function mount()
    {
        $this->user = Auth::user();
        $user = $this->user;
        $this->email = $user->email;
    }
    public function render()
    {
        $user = $this->user;
        return view('livewire.user.edit-user-email-modal', compact('user'));
    }

    public function submit()
    {
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ]);

        $this->user->update([
            'email' => $this->email,
            'email_verified_at' => null,
        ]);

        $this->dispatch('success', __('Profile Updated'));

        return redirect('/verify-email');
    }
}
