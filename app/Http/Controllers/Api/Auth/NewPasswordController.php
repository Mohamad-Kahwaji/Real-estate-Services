<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class NewPasswordController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'phone'    => 'required|string',
            'code'     => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = OtpCode::check($request->phone, $request->code);

        if ($result !== 'ok') {
            $message = match ($result) {
                'expired'   => 'The verification code has expired.',
                'exhausted' => 'Too many attempts. Please request a new code.',
                default     => 'The verification code is invalid.',
            };

            return response()->json(['status' => false, 'message' => $message], 422);
        }

        $user = User::where('phone', $request->phone)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        return response()->json([
            'status'  => true,
            'message' => 'Password reset successfully.',
        ]);
    }
}
