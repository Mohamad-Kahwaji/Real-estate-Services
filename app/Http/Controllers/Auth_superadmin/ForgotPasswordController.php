<?php

namespace App\Http\Controllers\Auth_superadmin;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\Superadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('super_admin.auth_superadmin.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $superadmin = Superadmin::where('email', $request->email)->first();

        if (! $superadmin) {
            return back()->withErrors(['email' => 'No super admin account found with this email.'])->withInput();
        }

        OtpCode::where('phone', $request->email)->delete();

        $devMode = ! config('services.ultramsg.enabled', false);
        $code    = $devMode ? '123456' : str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        OtpCode::create([
            'phone'      => $request->email,
            'code'       => $code,
            'attempts'   => 0,
            'expires_at' => now()->addMinutes(10),
        ]);

        if ($devMode) {
            Log::info("[OTP Superadmin] {$request->email} → {$code}");
        } else {
            Mail::raw(
                "Your super admin password reset code is: {$code}\nValid for 10 minutes.",
                fn ($m) => $m->to($request->email)->subject('Super Admin Password Reset Code')
            );
        }

        session(['superadmin_reset_email' => $request->email]);

        return redirect()->route('superadmin.reset-password.verify');
    }

    public function showVerify()
    {
        if (! session('superadmin_reset_email')) {
            return redirect()->route('superadmin.forgot-password');
        }

        return view('super_admin.auth_superadmin.reset-password', [
            'email' => session('superadmin_reset_email'),
        ]);
    }

    public function savePassword(Request $request)
    {
        $email = session('superadmin_reset_email');

        if (! $email) {
            return redirect()->route('superadmin.forgot-password');
        }

        $request->validate([
            'code'                  => 'required|digits:6',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
        ]);

        $result = OtpCode::check($email, $request->code);

        if ($result !== 'ok') {
            $msg = match ($result) {
                'expired'   => 'The verification code has expired.',
                'exhausted' => 'Too many attempts. Please request a new code.',
                default     => 'The verification code is invalid.',
            };
            return back()->withErrors(['code' => $msg]);
        }

        $superadmin = Superadmin::where('email', $email)->firstOrFail();
        $superadmin->update(['password' => Hash::make($request->password)]);
        session()->forget('superadmin_reset_email');

        return redirect()->route('loginsa.create')->with('status', 'Password reset successfully. Please sign in.');
    }
}
