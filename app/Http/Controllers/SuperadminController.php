<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Report;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Subcategory;
use App\Models\User;
use App\Notifications\InvoiceCreated;
use App\Notifications\UserDatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SuperadminController extends Controller
{
    // Render the superadmin login page.
    public function showLoginForm()
    {
        return view('auth.login-admin');
    }

    // Load the superadmin dashboard with aggregated stats, recent activity, and unread notifications.
    public function index(){
        $superadmin = Auth::guard('superadmins')->user();

        $totalUsers          = User::count();
        $totalAdmins         = Admin::count();
        $activeAdmins        = Admin::where('is_active', true)->count();
        $inactiveAdmins      = Admin::where('is_active', false)->count();

        $totalBusinesses     = Business::count();
        $pendingBusinesses   = Business::where('status', 'pending')->count();
        $approvedBusinesses  = Business::where('status', 'approved')->count();

        $totalServices       = Service::count();
        $pendingServices     = Service::where('status', 'pending')->count();
        $approvedServices    = Service::where('status', 'approved')->count();

        $totalReports        = Report::count();
        $pendingReports      = Report::where('status', 'pending')->count();

        $totalRequests       = ServiceRequest::count();
        $pendingRequests     = ServiceRequest::where('status', 'pending')->count();

        $totalCategories     = Category::count();
        $totalSubcategories  = Subcategory::count();
        $totalCities         = City::count();

        $recentBusinesses    = Business::with(['user', 'city'])->latest()->take(5)->get();
        $recentReports       = Report::with(['service', 'user'])->latest()->take(5)->get();
        $pendingServicesList = Service::with(['business', 'category'])->where('status', 'pending')->latest()->take(5)->get();
        $notifications       = $superadmin->unreadNotifications()->latest()->take(15)->get();
        $unreadCount         = $superadmin->unreadNotifications()->count();

        return view('super_admin.dash', compact(
            'superadmin',
            'totalUsers', 'totalAdmins', 'activeAdmins', 'inactiveAdmins',
            'totalBusinesses', 'pendingBusinesses', 'approvedBusinesses',
            'totalServices', 'pendingServices', 'approvedServices',
            'totalReports', 'pendingReports',
            'totalRequests', 'pendingRequests',
            'totalCategories', 'totalSubcategories', 'totalCities',
            'recentBusinesses', 'recentReports', 'pendingServicesList',
            'notifications', 'unreadCount'
        ));
    }

    // List all admins with their roles and permissions for the superadmin management view.
    public function adminindex(){
      $admins = Admin::with(['roles.permissions', 'permissions'])->get();
      $allPermissions = \Spatie\Permission\Models\Permission::where('guard_name', 'admins')->get();
      $roles = \Spatie\Permission\Models\Role::where('guard_name', 'admins')->with('permissions')->get();
      return view('super_admin.admins', compact('admins', 'allPermissions', 'roles'));
    }

    // Update the superadmin's name, email, and optionally their password after verifying the current one.
    public function updateProfile(Request $request)
    {
        $superadmin = Auth::guard('superadmins')->user();

        $rules = [
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:superadmins,email,' . $superadmin->id,
        ];

        if ($request->filled('new_password')) {
            $rules['current_password'] = 'required|string';
            $rules['new_password']     = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $superadmin->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $data = [
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('new_password')) {
            $data['password'] = Hash::make($validated['new_password']);
        }

        $superadmin->update($data);

        return redirect()->route('indexsuperadmin')->with('success', 'Profile updated successfully.');
    }

    // Display all service requests with their related user, service, and business data.
    public function serviceRequests()
    {
        $requests = ServiceRequest::with(['user', 'service.business', 'service.category', 'business'])
            ->latest()->get();
        return view('super_admin.servicerequests', compact('requests'));
    }

    // Approve a service request and notify the requesting user.
    public function approveServiceRequest($id)
    {
        $req = ServiceRequest::with(['user', 'service'])->findOrFail($id);
        $req->update(['status' => 'approved']);

        $req->user?->notify(new InvoiceCreated(
            'Service Request Approved',
            'Your service request has been approved.',
            ['type' => 'service_request', 'request_id' => $req->id]
        ));

        return redirect()->route('superadmin.service-requests')->with('success', 'Service request approved successfully.');
    }

    // Reject a service request and notify the requesting user.
    public function rejectServiceRequest($id)
    {
        $req = ServiceRequest::with(['user', 'service'])->findOrFail($id);
        $req->update(['status' => 'rejected']);

        $req->user?->notify(new UserDatabaseNotification(
            'Service Request Rejected',
            'Your service request has been rejected.',
            ['type' => 'service_request', 'request_id' => $req->id]
        ));

        return redirect()->route('superadmin.service-requests')->with('success', 'Service request rejected.');
    }

}
