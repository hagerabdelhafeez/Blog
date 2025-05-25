<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class Profile extends Component
{
    public $tab = null;
    public $tabName = 'personal_details';
    public $name, $email, $username, $bio;
    protected $queryString = ['tab' => ['keep' => true]];

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = Request('tab') ?? $this->tabName;

        $user = User::findOrFail(Auth::user()->id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;
    }

    public function updatePersonalDetails()
    {
        $user = User::findOrFail(Auth::user()->id);
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $user->id,
        ]);
        $user->name = $this->name;
        $user->username = $this->username;
        $user->bio = $this->bio;

        $updated = $user->save();
        sleep(0.5);
        if ($updated) {
            $this->dispatch('swalAlert', [
                'title' => 'Personal Details Updated Successfully',
                'icon' => 'success',
                'draggable' => true
            ]);
            $this->dispatch('updateTopUserInfo')->to(TopUserInfo::class);
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true
            ]);
        }
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => User::findOrFail(Auth::user()->id)
        ]);
    }
}
