<?php

namespace App\Livewire\User;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditUserModal extends Component
{

    use WithFileUploads;

    public $user;
    public $name;
    public $profile_photo_path;
    public $address_line_1;
    public $address_line_2;
    public $city;
    public $postal_code;
    public $state;
    public $country;
    public $avatar;

    public function mount()
    {
        $this->user = Auth::user();
        $user = User::findOrFail($this->user->id);
        $this->name = $user->name ?? null;
        $this->profile_photo_path = $user->profile_photo_path ?? null;
        $this->address_line_1 = $user->getDefaultAddressAttribute()->address_line_1 ?? null;
        $this->address_line_2 = $user->getDefaultAddressAttribute()->address_line_2 ?? null;
        $this->city = $user->getDefaultAddressAttribute()->city ?? null;
        $this->postal_code = $user->getDefaultAddressAttribute()->postal_code ?? null;
        $this->state = $user->getDefaultAddressAttribute()->state ?? null;
        $this->country = $user->getDefaultAddressAttribute()->country ?? null;
    }

    public function render()
    {
        $user = $this->user;
        $countries = json_decode(file_get_contents(storage_path('app/public/countries.json')), true);
        return view('livewire.user.edit-user-modal', compact('user', 'countries'));
    }

    protected $rules = [
        'name' => 'required',
        'address_line_1' => 'nullable',
        'address_line_2' => 'nullable',
        'city' => 'nullable',
        'country' => 'nullable',
        'postal_code' => 'nullable',
        'state' => 'nullable',
        'avatar' => 'nullable|image|max:1024',
    ];

    public function submit()
    {
        $this->validate();

        $dataUser = [
            'name' => $this->name,
        ];
        $user = $this->user;


        if ($this->avatar) {
            $imaage = $user->addMedia($this->avatar)->toMediaCollection('avatar');
            $dataUser['avatar'] = $imaage->id . '/' . $imaage->file_name;
            $dataUser['profile_photo_path'] = $imaage->id . '/' . $imaage->file_name;
        } else {
            $dataUser['avatar'] = $this->profile_photo_path;
            $dataUser['profile_photo_path'] = $this->profile_photo_path;
        }

        $dataAddress = [
            'address_line_1' => $this->address_line_1,
            'address_line_2' => $this->address_line_2,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'state' => $this->state,
        ];

        $userAddress = Address::where('user_id', $user->id)->first();
        if ($userAddress) {
            $userAddress->update($dataAddress);
        } else {
            $userAddress = new Address();
            $userAddress->user_id = $user->id;
            $userAddress->address_line_1 = $this->address_line_1;
            $userAddress->address_line_2 = $this->address_line_2;
            $userAddress->city = $this->city;
            $userAddress->country = $this->country;
            $userAddress->postal_code = $this->postal_code;
            $userAddress->state = $this->state;
            $userAddress->type = '1';
            $userAddress->save();
        }
        $user->update($dataUser);

        $this->dispatch('success', __('Profile Updated'));
    }
}
