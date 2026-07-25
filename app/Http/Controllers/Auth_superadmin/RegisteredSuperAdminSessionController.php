<?php

namespace App\Http\Controllers\Auth_superadmin;

use App\Http\Controllers\Controller;
use App\Models\Superadmin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredSuperAdminSessionController extends Controller
{
    // Returns the super-admin registration form — bootstrap only, closed once a superadmin already exists.
    public function create(): View|RedirectResponse
    {
        if (Superadmin::exists()) {
            return redirect()->route('loginsa.create')
                ->withErrors(['email' => 'Super admin registration is closed. Contact an existing super admin for access.']);
        }

        return view('super_admin.auth_superadmin.auth-register-superadmin');
    }

    // Validates input, creates the (only) superadmin account, and logs them in — blocked once one already exists.
    public function store(Request $request): RedirectResponse
    {
        if (Superadmin::exists()) {
            return redirect()->route('loginsa.create')
                ->withErrors(['email' => 'Super admin registration is closed. Contact an existing super admin for access.']);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:superadmins,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $superadmin = Superadmin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('superadmins')->login($superadmin);

        return redirect()->route('indexsuperadmin');
    }
}
