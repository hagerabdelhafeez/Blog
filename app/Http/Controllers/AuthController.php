<?php

namespace App\Http\Controllers;

use App\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Helpers\CMail;
use App\Models\User;

class AuthController extends Controller
{
    public function loginForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Login',
        ];
        return view('back.pages.auth.login', $data);
    }

    public function forgotForm(Request $request)
    {
        $data = [
            'pageTitle' => 'Forgot Password',
        ];
        return view('back.pages.auth.forgot', $data);
    }

    public function loginHandler(Request $request)
    {
        $fieldType = filter_var($request->login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if ($fieldType == 'email') {
            $request->validate([
                'login_id' => 'required|email|exists:users,email',
                'password' => 'required|min:5',
            ], [
                'login_id.required' => 'Enter your email or username.',
                'login_id.email' => 'Invalid email address.',
                'login_id.exists' => 'No account found for this email.',
            ]);
        } else {
            $request->validate([
                'login_id' => 'required|exists:users,username',
                'password' => 'required|min:5',
            ], [
                'login_id.required' => 'Enter your username or email.',
                'login_id.exists' => 'No account found for this username.',
            ]);
        }
        $creds = array(
            $fieldType => $request->login_id,
            'password' => $request->password,
        );
        if (Auth::attempt($creds)) {
            // Check if account is inactive
            if (Auth::user()->status == UserStatus::Inactive) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is currently inactive. Please, contact support at (support@blog.com) for further assistance.');
            }
            // Check if account is pending
            if (Auth::user()->status == UserStatus::Pending) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('admin.login')->with('fail', 'Your account is currently pending approval. Please, check your email for further instructions or contact support at (support@blog.com).');
            }
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login')->withInput()->with('fail', 'Invalid credentials');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'The :attribute is required.',
            'email.email' => 'Invalid email address.',
            'email.exists' => 'We can not find a user with this email address.',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = $this->createOrUpdatePasswordResetToken($user->email);

        if ($this->sendPasswordResetEmail($user, $token)) {
            return redirect()->route('admin.forgot')
                ->with('success', 'Password reset link has been sent to your email address.');
        }

        return redirect()->route('admin.forgot')
            ->with('fail', 'Something went wrong. Please try again later.');
    }


    private function createOrUpdatePasswordResetToken(string $email): string
    {
        $token = base64_encode(Str::random(64));
        $tokenData = [
            'token' => $token,
            'created_at' => Carbon::now(),
        ];

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            $tokenData
        );

        return $token;
    }


    private function sendPasswordResetEmail(User $user, string $token): bool
    {
        $resetUrl = route('admin.reset_password_form', ['token' => $token]);

        $emailBody = view('email-templates.forgot-template', [
            'link' => $resetUrl,
            'user' => $user,
        ])->render();

        $mailConfig = [
            'recipient_address' => $user->email,
            'recipient_name' => $user->name,
            'subject' => 'Reset Password',
            'body' => $emailBody
        ];

        return CMail::send($mailConfig);
    }
}
