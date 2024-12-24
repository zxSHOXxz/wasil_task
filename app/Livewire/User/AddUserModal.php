<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AddUserModal extends Component
{
    use WithFileUploads;

    public $user_id;
    public $name;
    public $email;
    public $role;
    public $avatar;
    public $saved_avatar;
    public $password;

    public $edit_mode = false;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required|email',
        'role' => 'required|string',
        'password' => 'required|string',
        'avatar' => 'required|image|max:1024',
    ];

    protected $listeners = [
        'delete_user' => 'deleteUser',
        'update_user' => 'updateUser',
        'new_user' => 'hydrate',
    ];

    public function render()
    {
        $roles = Role::all();

        $roles_description = [
            'administrator' => 'Best for business owners and company administrators',
            'editor' => 'Best for developers or people primarily using the API',
        ];

        foreach ($roles as $i => $role) {
            $roles[$i]->description = $roles_description[$role->name] ?? '';
        }

        return view('livewire.user.add-user-modal', compact('roles'));
    }

    public function submit()
    {
        $this->validate();

        DB::transaction(function () {
            $data = [
                'name' => $this->name,
                'password' => Hash::make($this->password),
            ];

            if ($this->avatar) {
                $data['avatar'] = $this->avatar->store('avatar', 'public');
                $data['profile_photo_path'] = $this->avatar->store('avatar', 'public');
            } else {
                $data['avatar'] = null;
            }

            $data['email'] = $this->email;
            $user = new User();
            $user->name = $data['name'];
            $user->password = $data['password'];
            $user->email = $data['email'];
            $user->phone = '0598241105';
            $user->avatar = $data['avatar'];
            $user->profile_photo_path = $data['profile_photo_path'];
            $user->save();

            $user->assignRole($this->role);
            $this->dispatch('success', __('New user created'));
            // if ($this->edit_mode) {
            //     $user->syncRoles($this->role);

            //     $this->dispatch('success', __('User updated'));
            // } else {
            // }
        });
        $this->reset();
    }

    public function deleteUser($id)
    {
        // Prevent deletion of current user
        if ($id == Auth::id()) {
            $this->dispatch('error', 'User cannot be deleted');
            return;
        }

        // Delete the user record with the specified ID
        User::destroy($id);

        // Emit a success event with a message
        $this->dispatch('success', 'User successfully deleted');
    }

    public function updateUser($id)
    {
        $this->edit_mode = true;

        $user = User::find($id);

        $this->user_id = $user->id;
        $this->saved_avatar = $user->profile_photo_path;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles?->first()->name ?? '';
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->name = $this->name;
        $this->email = $this->email;
        $this->role = $this->role;
    }
}
