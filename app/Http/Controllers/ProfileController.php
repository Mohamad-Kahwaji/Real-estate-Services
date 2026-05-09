<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Business;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = auth('users')->user();

        $accounts = Business::with(['activeType', 'city'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        $services = Service::with(['category', 'subcategory', 'business'])
            ->where('status', 'approved')
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'user' => $user,
                'accounts' => $accounts,
                'services' => $services,
            ]
        ], 200);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = auth('users')->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user
        ], 200);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth('users')->user();

        Auth::guard('users')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status' => true,
            'message' => 'Account deleted successfully.'
        ], 200);
    }
}
