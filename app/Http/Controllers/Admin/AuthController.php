<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Incorrect email or password. Please try again.'])
                ->onlyInput('email');
        }

        // Deliberately checked after the password matched, so a deactivated user
        // is told why they cannot get in instead of doubting their password.
        if (! Auth::user()->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()
                ->withErrors(['email' => 'This account has been deactivated. Please contact an administrator.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * The one-off screen a user lands on when an administrator created their
     * account or reset their password. EnsureUserIsAdmin funnels them here and
     * lets nothing else through until they have chosen their own password.
     */
    public function showPasswordChange()
    {
        if (! Auth::user()->must_change_password) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.password-change');
    }

    public function updatePasswordChange(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Please choose a password different from the temporary one.']);
        }

        $user->update([
            'password' => $request->input('password'),
            'must_change_password' => false,
        ]);

        return redirect()->route('admin.dashboard')->with('status', 'Password set. Welcome aboard.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
