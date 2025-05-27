<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Helpers\CMail;
use App\Models\UserSocialLink;

class Profile extends Component
{
    public $tab = null;
    public $tabName = 'personal_details';
    protected $queryString = ['tab' => ['keep' => true]];
    public $name, $email, $username, $bio;
    public $current_password, $new_password, $new_password_confirmation;
    public $facebook_url, $instagram_url, $youtube_url, $linkedin_url, $twitter_url, $github_url;

    protected $listeners = [
        'updateProfile' => '$refresh'
    ];

    public function selectTab($tab)
    {
        $this->tab = $tab;
    }

    public function mount()
    {
        $this->tab = Request('tab') ?? $this->tabName;

        $user = User::with('social_links')->findOrFail(Auth::user()->id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->username = $user->username;
        $this->bio = $user->bio;

        // Social Links
        if (!is_null($user->social_links)) {
            $this->facebook_url = $user->social_links->facebook_url;
            $this->instagram_url = $user->social_links->instagram_url;
            $this->youtube_url = $user->social_links->youtube_url;
            $this->linkedin_url = $user->social_links->linkedin_url;
            $this->twitter_url = $user->social_links->twitter_url;
            $this->github_url = $user->social_links->github_url;
        }
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

    public function updatePassword()
    {
        $user = User::findOrFail(Auth::user()->id);
        $this->validate([
            'current_password' => [
                'required',
                'min:5',
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        return $fail('Your current password does not match our records.');
                    }
                }
            ],
            'new_password' => 'required|min:5|confirmed',
        ]);
        $updated = $user->update([
            'password' => Hash::make($this->new_password)
        ]);
        if ($updated) {
            $data = array(
                'user' => $user,
                'new_password' => $this->new_password,
            );
            $mail_body = view('email-templates.password-changes-template', $data)->render();
            $mail_config = array(
                'recipient_address' => $user->email,
                'recipient_name' => $user->name,
                'subject' => 'Password Changes',
                'body' => $mail_body,
            );
            CMail::send($mail_config);
            Auth::logout();
            Session::flash('info', 'Your password has been changed successfully. Please login with your new password.');
            $this->redirectRoute('admin.login');
        } else {
            $this->dispatch('swalAlert', [
                'title' => "Oops...\n Something went wrong!",
                'icon' => 'error',
                'draggable' => true
            ]);
        }
    }

    public function updateSocialLinks()
    {
        $this->validate([
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'github_url' => 'nullable|url',
        ]);
        $user = User::findOrFail(Auth::user()->id);
        $data = array(
            'facebook_url' => $this->facebook_url,
            'instagram_url' => $this->instagram_url,
            'youtube_url' => $this->youtube_url,
            'linkedin_url' => $this->linkedin_url,
            'twitter_url' => $this->twitter_url,
            'github_url' => $this->github_url,
        );
        if (!is_null($user->social_links)) {
            $query = $user->social_links()->update($data);
        } else {
            $data['user_id'] = $user->id;
            $query = UserSocialLink::insert($data);
        }
        if ($query) {
            $this->dispatch('swalAlert', [
                'title' => 'Social Links Updated Successfully',
                'icon' => 'success',
                'draggable' => true
            ]);
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
