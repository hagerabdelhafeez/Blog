<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;


class AdminController extends Controller
{
    public function adminDashboard(Request $request)
    {
        $data = [
            'pageTitle' => 'Dashboard',
        ];
        return view('back.pages.dashboard', $data);
    }

    public function logoutHandler(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login')->with('fail', 'You are now logged out.');
    }

    public function profileView(Request $request)
    {
        $data = [
            'pageTitle' => 'Profile',
        ];
        return view('back.pages.profile', $data);
    }

    public function updateProfilePicture(Request $request)
    {
        if (!$request->hasFile('profilePictureFile')) {
            return response()->json(['status' => 0, 'message' => 'No file uploaded.']);
        }

        $user = User::findOrFail(Auth::user()->id);
        $path = storage_path('app/public/users/');

        // Ensure the upload directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $file = $request->file('profilePictureFile');
        $old_picture = $user->getAttributes()['picture'];
        $filename = 'IMG_' . uniqid() . '.png';

        try {
            $upload = Kropify::getFile($file, $filename)->maxWoH(255)->save($path);

            if ($upload) {
                if ($old_picture != null && File::exists($path . $old_picture)) {
                    File::delete($path . $old_picture);
                }

                $user->picture = $filename;
                if ($user->save()) {
                    return response()->json(['status' => 1, 'message' => 'Profile picture updated successfully.']);
                }
            }

            return response()->json(['status' => 0, 'message' => 'Failed to process the uploaded image.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 0, 'message' => 'Error updating profile picture: ' . $e->getMessage()]);
        }
    }
}
