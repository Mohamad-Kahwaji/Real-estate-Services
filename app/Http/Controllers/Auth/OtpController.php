<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    // عرض صفحة إدخال OTP
    public function show(Request $request)
    {
        if (! session('otp_phone')) {
            return redirect()->route('login');
        }

        return view('users.auth_user.otp-verify', [
            'phone' => session('otp_phone'),
        ]);
    }

    // التحقق من الكود
    public function verify(Request $request)
    {
        $phone = session('otp_phone');
        if (! $phone) return redirect()->route('login');

        $request->validate(['code' => 'required|digits:6']);

        $result = OtpCode::check($phone, $request->code);

        if ($result === 'ok') {
            $user = User::where('phone', $phone)->firstOrFail();
            Auth::guard('users')->login($user);
            session()->forget('otp_phone');
            return redirect()->route('dashi');
        }

        // إذا انتهت الصلاحية أو استنفدت المحاولات → عود للـ login
        if (in_array($result, ['expired', 'exhausted'])) {
            session()->forget('otp_phone');
            $msg = $result === 'expired'
                ? 'انتهت صلاحية الرمز. سجّل دخولك من جديد.'
                : 'تجاوزت 3 محاولات. سجّل دخولك من جديد.';
            return redirect()->route('login')->withErrors(['phone' => $msg]);
        }

        // رمز خاطئ — أظهر المحاولات المتبقية
        $otp       = OtpCode::where('phone', $phone)->latest()->first();
        $remaining = $otp ? 3 - $otp->attempts : 0;

        return back()->withErrors(['code' => "الرمز غير صحيح. متبقي {$remaining} محاولات."]);
    }

    // إعادة إرسال OTP
    public function resend(Request $request)
    {
        $phone = session('otp_phone');
        if (! $phone) return redirect()->route('login');

        OtpCode::sendTo($phone);

        return back()->with('success', 'تم إعادة إرسال الرمز.');
    }
}
