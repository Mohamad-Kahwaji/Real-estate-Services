<?php

namespace App\Http\Controllers\Auth_admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('super_admin.auth_admin.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $admin = Admin::where('email', $request->email)->first();

        if (! $admin) {
            return back()->withErrors(['email' => 'No admin account found with this email.'])->withInput();
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
            Log::info("[OTP Admin] {$request->email} → {$code}");
        } else {
            Mail::raw(
                "Your admin password reset code is: {$code}\nValid for 10 minutes.",
                fn ($m) => $m->to($request->email)->subject('Admin Password Reset Code')
            );
        }

        session(['admin_reset_email' => $request->email]);

        return redirect()->route('admin.reset-password.verify');
    }

    public function showVerify()
    {
        if (! session('admin_reset_email')) {
            return redirect()->route('admin.forgot-password');
        }

        return view('super_admin.auth_admin.reset-password', [
            'email' => session('admin_reset_email'),
        ]);
    }

    public function savePassword(Request $request)
    {
        $email = session('admin_reset_email');

        if (! $email) {
            return redirect()->route('admin.forgot-password');
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

        $admin = Admin::where('email', $email)->firstOrFail();
        $admin->update(['password' => Hash::make($request->password)]);
        session()->forget('admin_reset_email');

        return redirect()->route('logina.create')->with('status', 'Password reset successfully. Please sign in.');
    }
}
