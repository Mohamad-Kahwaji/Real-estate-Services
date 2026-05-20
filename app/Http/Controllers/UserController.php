<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    // List all users with their associated businesses for the super-admin panel.
    public function index(){
        $users = User::with('businesses')->latest()->get();
        return view('super_admin.users', compact('users'));
    }

    // Toggle a user's active status and cascade suspend/reactivate their businesses and services.
    public function toggleStatus(int $id)
    {
        $user = User::findOrFail($id);
        $isActive = ! $user->is_active;

        $user->update(['is_active' => $isActive]);

        if (! $isActive) {
            $user->businesses()
                ->where('status', 'approved')
                ->update(['status' => 'suspended']);

            DB::table('services')
                ->whereIn('business_id', $user->businesses()->pluck('id'))
                ->where('status', 'approved')
                ->update(['status' => 'suspended']);

            DB::table('sessions')
                ->where('user_id', $user->id)
                ->delete();
        } else {
            $user->businesses()
                ->where('status', 'suspended')
                ->update(['status' => 'approved']);

            DB::table('services')
                ->whereIn('business_id', $user->businesses()->pluck('id'))
                ->where('status', 'suspended')
                ->update(['status' => 'approved']);
        }

        $msg = $isActive ? 'User activated successfully.' : 'User suspended successfully.';
        return back()->with('success', $msg);
    }

    // Show the account settings page for the currently authenticated user.
    public function edit(){
        $user = auth()->user();
        return view('pages.pages-account-settings-account',compact('user'));
    }

    // Display the user dashboard with all available services.
    public function dash(){
        $user = auth('users')->user();
        $services = Service::all();
        return view('users.index',compact('user','services'));
    }

    // Update the authenticated user's name, phone, and email.
    public function update(Request $request){
        $user = auth('users')->user();
        $val = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);
        $user->update([
            'name' => $val['name'],
            'phone' => $val['phone'],
            'email' => $val['email'],
        ]);

        return redirect()->route('dash');
    }

    public function __construct(protected UserService $user_service)
    {
    }
}
