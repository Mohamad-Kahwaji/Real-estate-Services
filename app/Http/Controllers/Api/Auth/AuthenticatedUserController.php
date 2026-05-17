<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthenticatedUserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'phone'    => 'required|string|max:20',
            'password' => 'required',
        ]);

        if (! Auth::guard('users')->attempt($request->only('phone', 'password'))) {
            throw ValidationException::withMessages([
                'phone' => ['بيانات الدخول غير صحيحة'],
            ]);
        }

        $user = Auth::guard('users')->user();
        Auth::guard('users')->logout();

        $sent = OtpCode::sendTo($user->phone);

        if (! $sent) {
            return response()->json([
                'status'  => false,
                'message' => 'فشل إرسال رمز التحقق.',
            ], 500);
        }

        return response()->json([
            'status'  => true,
            'step'    => 'otp_sent',
            'message' => 'تم إرسال رمز التحقق عبر واتساب.',
            'phone'   => $user->phone,
        ]);
    }

    public function destroy(Request $request)
    {
        $user = $request->user();
        if ($user) $user->updateQuietly(['last_seen_at' => null]);

        $request->user()->currentAccessToken()->delete();

        return response()->json(['status' => true, 'message' => 'تم تسجيل الخروج بنجاح']);
    }
}
