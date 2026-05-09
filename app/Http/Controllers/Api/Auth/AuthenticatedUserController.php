<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedUserController extends Controller
{
    public function create(): View
    {
        return view('users.auth_user.auth-login-user');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required'],
        ]);

        if (Auth::guard('users')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashi'));
        }

        return back()->withErrors([
            'phone' => 'بيانات الدخول غير صحيحة',
        ])->onlyInput('phone');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('users')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
