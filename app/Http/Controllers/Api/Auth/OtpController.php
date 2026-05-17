<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\Request;

class OtpController extends Controller
{
    // POST /api/verify-otp   { phone, code }
    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'code'  => 'required|digits:6',
        ]);

        $result = OtpCode::check($request->phone, $request->code);

        if ($result === 'ok') {
            $user  = User::where('phone', $request->phone)->firstOrFail();
            $token = $user->createToken('user-token')->plainTextToken;

            return response()->json([
                'status'  => true,
                'message' => 'secure code verified successfully.',
                'token'   => $token,
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'phone' => $user->phone,
                ],
            ]);
        }

        $messages = [
            'invalid'   => 'the code is incorrect. Please try again.',
            'expired'   =>'please request a new code and try again.',
            'exhausted' => 'you have exceeded the maximum number of attempts. Please request a new code.',
        ];

        $remaining = null;
        if ($result === 'invalid') {
            $otp       = OtpCode::where('phone', $request->phone)->latest()->first();
            $remaining = $otp ? 3 - $otp->attempts : 0;
        }

        return response()->json([
            'status'             => false,
            'message'            => $messages[$result],
            'remaining_attempts' => $remaining,
        ], 422);
    }

    // POST /api/resend-otp   { phone }
    public function resend(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:20']);

        if (! User::where('phone', $request->phone)->exists()) {
            return response()->json(['status' => false, 'message' => 'the phone number is not registered.'], 404);
        }

        OtpCode::sendTo($request->phone);

        return response()->json(['status' => true, 'message' => 'renewed code sent successfully.']);
    }
}
