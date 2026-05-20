<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    // Returns the OTP entry page, redirecting to login if no pending OTP session exists.
    public function show(Request $request)
    {
        if (! session('otp_phone')) {
            return redirect()->route('login');
        }

        return view('users.auth_user.otp-verify', [
            'phone' => session('otp_phone'),
        ]);
    }

    // Verifies the submitted OTP code and logs the user in, or returns an error with remaining attempts.
    public function verify(Request $request)
    {
        $phone = session('otp_phone');
        if (! $phone) return redirect()->route('login');

        $request->validate(['code' => 'required|digits:6']);

        $result = OtpCode::check($phone, $request->code);

        if ($result === 'ok') {
            $user = User::where('phone', $phone)->firstOrFail();

            if (! $user->is_active) {
                session()->forget('otp_phone');
                return redirect()->route('login')->withErrors(['phone' => 'حسابك موقوف. تواصل مع الدعم.']);
            }

            Auth::guard('users')->login($user);
            session()->forget('otp_phone');
            return redirect()->route('dashi');
        }

        if (in_array($result, ['expired', 'exhausted'])) {
            session()->forget('otp_phone');
            $msg = $result === 'expired'
                ? 'انتهت صلاحية الرمز. سجّل دخولك من جديد.'
                : 'تجاوزت 3 محاولات. سجّل دخولك من جديد.';
            return redirect()->route('login')->withErrors(['phone' => $msg]);
        }

        $otp       = OtpCode::where('phone', $phone)->latest()->first();
        $remaining = $otp ? 3 - $otp->attempts : 0;

        return back()->withErrors(['code' => "الرمز غير صحيح. متبقي {$remaining} محاولات."]);
    }

    // Re-sends the OTP to the phone number stored in the current session.
    public function resend(Request $request)
    {
        $phone = session('otp_phone');
        if (! $phone) return redirect()->route('login');

        OtpCode::sendTo($phone);

        return back()->with('success', 'تم إعادة إرسال الرمز.');
    }
}
