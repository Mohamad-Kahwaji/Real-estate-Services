<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordResetLinkController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate(['phone' => 'required|string']);

        $user = User::where('phone', $request->phone)->first();

        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'No account found with this phone number.',
            ], 422);
        }

        OtpCode::sendTo($request->phone);

        return response()->json([
            'status'  => true,
            'message' => 'Verification code sent successfully.',
        ]);
    }
}
