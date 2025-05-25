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
    protected $queryString = ['tab' => ['keep' => true]];

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = Request('tab')?? $this->tabName;
    }

    public function render()
    {
        return view('livewire.admin.profile', [
            'user' => User::findOrFail(Auth::user()->id)
        ]);
    }
}
