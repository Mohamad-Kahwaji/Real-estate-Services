<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('users.auth_user.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();

        if (! $user) {
            return back()->withErrors(['phone' => __('app.phone_not_found')])->withInput();
        }

        OtpCode::sendTo($request->phone);
        session(['reset_phone' => $request->phone]);

        return redirect()->route('reset-password.verify');
    }

    public function showVerify()
    {
        if (! session('reset_phone')) {
            return redirect()->route('forgot-password');
        }

        return view('users.auth_user.reset-password', [
            'phone' => session('reset_phone'),
        ]);
    }

    public function savePassword(Request $request)
    {
        $phone = session('reset_phone');

        if (! $phone) {
            return redirect()->route('forgot-password');
        }

        $request->validate([
            'code'                  => 'required|digits:6',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $result = OtpCode::check($phone, $request->code);

        if ($result !== 'ok') {
            $msg = match ($result) {
                'expired'   => __('app.otp_expired'),
                'exhausted' => __('app.otp_exhausted'),
                default     => __('app.otp_invalid'),
            };
            return back()->withErrors(['code' => $msg]);
        }

        $user = User::where('phone', $phone)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);
        session()->forget('reset_phone');

        return redirect()->route('login')->with('success', __('app.password_reset_success'));
    }
}
