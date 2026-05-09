<?php

namespace App\Http\Controllers\Auth_admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedAdminSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('super_admin.auth_admin.auth-login-admin');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::guard('admins')->attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'These credentials do not match our records.',
            ])->onlyInput('email');
        }

        $admin = Auth::guard('admins')->user();

        if (!$admin->is_active) {
            Auth::guard('admins')->logout();

            return back()->withErrors([
                'email' => 'Your account has been deactivated. Please contact the super admin.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('dashi');
    }
}
