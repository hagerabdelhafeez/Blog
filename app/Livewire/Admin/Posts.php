<?php

namespace App\Livewire\Admin;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;
    public $perPage = 2;

    public function render()
    {
        return view('livewire.admin.posts', [
            'posts' => Auth::user()->type == 'superAdmin' ?
            Post::paginate($this->perPage) :
            Post::where('author_id', Auth::user()->id)->paginate($this->perPage),
        ]);
    }
}
