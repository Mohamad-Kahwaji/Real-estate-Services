<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserController extends Controller
{
    // Returns the user login form view.
    public function create()
    {
        return view('users.auth_user.auth-login-user');
    }

    // Validates credentials, checks account status, sends an OTP, and redirects to the OTP page.
    public function store(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string|max:20',
            'password' => 'required',
        ]);

        if (! Auth::guard('users')->attempt($request->only('phone', 'password'))) {
            return back()->withErrors(['phone' => 'بيانات الدخول غير صحيحة'])->onlyInput('phone');
        }

        $user = Auth::guard('users')->user();
        Auth::guard('users')->logout();

        if (! $user->is_active) {
            return back()->withErrors(['phone' => 'حسابك موقوف. تواصل مع الدعم.'])->onlyInput('phone');
        }

        $sent = OtpCode::sendTo($user->phone);

        if (! $sent) {
            return back()->withErrors(['phone' => 'فشل إرسال رمز التحقق. حاول مجدداً.'])->onlyInput('phone');
        }

        session(['otp_phone' => $user->phone]);

        return redirect()->route('otp.show');
    }

    // Logs out the user, clears the session, and redirects to the login page.
    public function destroy(Request $request)
    {
        $user = Auth::guard('users')->user();
        if ($user) $user->updateQuietly(['last_seen_at' => null]);

        Auth::guard('users')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
