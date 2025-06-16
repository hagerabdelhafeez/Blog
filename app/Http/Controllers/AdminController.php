<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;
use SawaStacks\Utils\Kropify;
use App\Models\GeneralSetting;


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

    public function generalSettings(Request $request)
    {
        $data = [
            'pageTitle' => 'General Settings',
        ];
        return view('back.pages.general_settings', $data);
    }

    public function updateLogo(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            $path = storage_path('app/public/settings/');
            $old_logo = $settings->site_logo;
            $file = $request->file('site_logo');
            $file_name = 'logo_' . uniqid() . '.png';
            if ($request->hasFile('site_logo')) {
                $uplodad = $file->move($path, $file_name);
                if ($uplodad) {
                    if ($old_logo != null && File::exists($path . $old_logo)) {
                        File::delete($path . $old_logo);
                    }
                    $settings->update([
                        'site_logo' => $file_name
                    ]);
                    return response()->json(['status' => 1, 'message' => 'Logo updated successfully.', 'image_path' => $path . $file_name]);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Failed to process the uploaded image.']);
                }
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Make sure you updated general settings form first.']);
        }
    }

    public function updateFavicon(Request $request)
    {
        $settings = GeneralSetting::take(1)->first();
        if (!is_null($settings)) {
            $path = storage_path('app/public/settings/');
            $old_favicon = $settings->site_favicon;
            $file = $request->file('site_favicon');
            $file_name = 'favicon_' . uniqid() . '.png';
            if ($request->hasFile('site_favicon')) {
                $upload  = $file->move($path, $file_name);
                if ($upload) {
                    if ($old_favicon != null && File::exists($path . $old_favicon)) {
                        file::delete($path . $old_favicon);
                    }
                    $settings->update([
                        'site_favicon' => $file_name
                    ]);
                    return response()->json(['status' => 1, 'message' => 'Site Favicon updated successfully.', 'image_path' => $path . $file_name]);
                } else {
                    return response()->json(['status' => 0, 'message' => 'Failed to process the uploaded favicon.']);
                }
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Make sure you updated general settings form first.']);
        }
    }
}
