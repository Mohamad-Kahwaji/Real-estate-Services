<?php

namespace App\Http\Controllers\Auth_superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSuperAdminSessionController extends Controller
{
    public function create(): View
    {
        return view('super_admin.auth_superadmin.auth-login-superadmin');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('superadmins')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('indexsuperadmin');
        }

        if (Auth::guard('admins')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('adminsindex');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('superadmin')->check()) {
            Auth::guard('superadmin')->logout();
        } elseif (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('loginsa.create');
    }
}
