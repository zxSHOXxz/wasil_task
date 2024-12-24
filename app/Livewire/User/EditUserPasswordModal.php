<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class EditUserPasswordModal extends Component
{
    public $user;

    public $current_password;
    public $password;
    public $password_confirmation;


    public function submit()
    {
        // التحقق من صحة البيانات المدخلة
        $this->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        // التحقق مما إذا كانت كلمة المرور الحالية صحيحة
        if (!Hash::check($this->current_password, $this->user->password)) {
            // إذا كانت كلمة المرور غير صحيحة، يمكن إضافة رسالة خطأ
            $this->dispatch('error', __('Current Password Not Correct'));

            return;
        }

        // تحديث كلمة المرور الجديدة
        $this->user->update([
            'password' => Hash::make($this->password),
        ]);

        // رسالة تأكيد بنجاح التحديث
        $this->dispatch('success', __('Password Updated'));
    }

    public function mount()
    {
        $this->user = Auth::user();
    }

    public function render()
    {
        return view('livewire.user.edit-user-password-modal');
    }
}
