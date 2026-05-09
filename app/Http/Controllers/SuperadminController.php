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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperadminController extends Controller
{
     public function showLoginForm()
    {
        return view('auth.login-admin');
    }

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

        $recentBusinesses    = Business::with('user')->latest()->take(5)->get();
        $recentReports       = Report::with(['service', 'user'])->latest()->take(5)->get();

        return view('super_admin.dash', compact(
            'superadmin',
            'totalUsers', 'totalAdmins', 'activeAdmins', 'inactiveAdmins',
            'totalBusinesses', 'pendingBusinesses', 'approvedBusinesses',
            'totalServices', 'pendingServices', 'approvedServices',
            'totalReports', 'pendingReports',
            'totalRequests', 'pendingRequests',
            'totalCategories', 'totalSubcategories', 'totalCities',
            'recentBusinesses', 'recentReports'
        ));
    }

    public function adminindex(){
      $admins = Admin::with('permissions')->get();
      $allPermissions = \Spatie\Permission\Models\Permission::where('guard_name', 'admins')->get();
      return view('super_admin.admins', compact('admins', 'allPermissions'));
    }

}
