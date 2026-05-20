<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Loads the profile edit page with the user's business accounts and active type / city details.
    public function edit()
    {
        /** @var \App\Models\User $user */
        $user = auth('users')->user();

        $accounts = Business::with(['activeType', 'city'])
            ->where('user_id', $user->id)
            ->get();

        return view('users.profile', compact('user', 'accounts'));
    }

    // Validates and saves changes to the authenticated user's profile, hashing a new password if provided.
    public function update(ProfileUpdateRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth('users')->user();

        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill($data)->save();

        return redirect()->route('profile.edit')
            ->with('success', __('app.profile_updated'));
    }

    // Verifies the user's password, deletes their account, and invalidates the session.
    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        /** @var \App\Models\User $user */
        $user = auth('users')->user();

        Auth::guard('users')->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'status'  => true,
            'message' => 'Account deleted successfully.'
        ], 200);
    }
}
