<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedUserController extends Controller
{
    public function create()
    {
        return view('users.auth_user.auth-login-user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string|max:20',
            'password' => 'required',
        ]);

        // 1. تحقق من الاسم والباسورد
        if (! Auth::guard('users')->attempt($request->only('phone', 'password'))) {
            return back()->withErrors(['phone' => 'بيانات الدخول غير صحيحة'])->onlyInput('phone');
        }

        $user = Auth::guard('users')->user();
        Auth::guard('users')->logout(); // خروج مؤقت ريثما يتحقق من OTP

        // 2. أرسل OTP عبر واتساب
        $sent = OtpCode::sendTo($user->phone);

        if (! $sent) {
            return back()->withErrors(['phone' => 'فشل إرسال رمز التحقق. حاول مجدداً.'])->onlyInput('phone');
        }

        // 3. احفظ الرقم في الجلسة وانتقل لصفحة OTP
        session(['otp_phone' => $user->phone]);

        return redirect()->route('otp.show');
    }

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
