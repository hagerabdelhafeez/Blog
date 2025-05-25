<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TopUserInfo extends Component
{
    protected $listeners = [
        'updateTopUserInfo' => '$refresh'
    ];
    public function render()
    {
        return view('livewire.admin.top-user-info', [
            'user' => User::findOrFail(Auth::user()->id)
        ]);
    }
}
