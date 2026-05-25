<?php

namespace App\Http\Controllers;

use App\Models\Activetype;
use App\Models\Admin;
use App\Models\Business;
use App\Models\City;
use App\Models\ServiceRequest;
use App\Models\Superadmin;
use App\Models\User;
use App\Notifications\UserDatabaseNotification;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // List all businesses with server-side search (name, license) and filter by status and city.
    public function index(Request $request)
    {
        $term   = $request->search ? '%' . $request->search . '%' : null;
        $status = $request->input('status', 'pending');

        $businesses = Business::with(['user', 'city', 'activeType'])
            ->when($status !== 'all', fn($q) => $q->where('status', $status))
            ->when($request->city_id, fn($q) => $q->where('city_id', $request->city_id))
            ->when($term, fn($q) => $q->where(fn($q2) =>
                $q2->where('job_name_ar', 'like', $term)
                   ->orWhere('job_name_en', 'like', $term)
                   ->orWhere('license_number', 'like', $term)
                   ->orWhereHas('user', fn($u) => $u->where('name', 'like', $term))
            ))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $statusCounts = [
            'pending'  => Business::where('status', 'pending')->count(),
            'approved' => Business::where('status', 'approved')->count(),
            'rejected' => Business::where('status', 'rejected')->count(),
            'all'      => Business::count(),
        ];

        $cities = City::orderBy('name_ar')->get();

        return view('super_admin.businesses', compact('businesses', 'cities', 'statusCounts'));
    }

    // Show a single business in the superadmin businesses view.
    public function show($id)
    {
        $business = Business::with(['user', 'city', 'activeType'])->findOrFail($id);
        return view('super_admin.businesses', ['businesses' => collect([$business])]);
    }

    // Render the form for a user to create a new business account application.
    public function create()
    {
        $activetypes = Activetype::all();
        $cities      = City::all();

        return view('users.business-account', compact('activetypes', 'cities'));
    }

    // Show the edit form for a user's own business account.
    public function edit($id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', auth('users')->id())
            ->firstOrFail();

        $activetypes = Activetype::all();
        $cities      = City::all();

        return view('users.business-account', compact('business', 'activetypes', 'cities'));
    }

    // Validate and persist a new business account request, then notify admins.
    public function store(Request $request)
    {
        $val = $request->validate([
            'activetype_id'  => 'required|exists:activetypes,id',
            'license_number' => 'required|integer',
            'job_name_ar'    => 'required|string',
            'job_name_en'    => 'required|string',
            'activites'      => 'required|string',
            'details'        => 'required|string',
            'city_id'        => 'required|exists:cities,id',
            'image'          => 'nullable|image|max:4096',
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
        ]);

        if ($request->hasFile('image')) {
            $val['image'] = $request->file('image')->store('businesses', 'public');
        }

        $val['user_id'] = auth('users')->id();
        $val['status']  = 'pending';

        $business = Business::create($val);

        app(AdminPushNotificationService::class)->send(
            'New Business Account Request',
            'There is a new business account waiting for approval.',
            [
                'type'        => 'business_account_request',
                'business_id' => $business->id,
            ]
        );

        $dbNotification = new UserDatabaseNotification(
            'New Business Account Request',
            'There is a new business account waiting for approval.',
            ['type' => 'business_account_request', 'business_id' => $business->id]
        );
        Superadmin::all()->each(fn ($sa) => $sa->notify($dbNotification));
        Admin::all()->each(fn ($a) => $a->notify($dbNotification));

        if ($request->wantsJson()) {
            return response()->json([
                'status'  => true,
                'message' => 'Business account request submitted successfully. Please wait for approval.',
                'data'    => $business,
            ], 201);
        }

        return redirect()->route('user.business.create')
            ->with('success', 'Your business account request has been submitted. Please wait for approval.');
    }

    // Update the authenticated user's own business account.
    public function update(Request $request, $id)
    {
        $business = Business::where('id', $id)
            ->where('user_id', auth('users')->id())
            ->firstOrFail();

        $val = $request->validate([
            'activetype_id'  => 'required|exists:activetypes,id',
            'license_number' => 'required|integer',
            'job_name_ar'    => 'required|string',
            'job_name_en'    => 'required|string',
            'activites'      => 'required|string',
            'details'        => 'required|string',
            'city_id'        => 'required|exists:cities,id',
            'image'          => 'nullable|image|max:4096',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        if ($request->hasFile('image')) {
            $val['image'] = $request->file('image')->store('businesses', 'public');
        }

        $business->update($val);

        return redirect()->route('business.dashboard')
            ->with('success', 'Business account updated successfully.');
    }

    // Return all businesses with a pending status as a JSON response.
    public function pending()
    {
        $accounts = Business::with(['user', 'city'])
            ->where('status', 'pending')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $accounts
        ], 200);
    }

    // Approve a pending business account and notify its owner.
    public function approve($id)
    {
        $account = Business::findOrFail($id);
        $account->update(['status' => 'approved']);

        $account->user->notify(new UserDatabaseNotification(
            'Business Account Approved',
            'Your business account has been approved.',
            ['type' => 'business_account', 'business_id' => $account->id]
        ));

        return redirect()->route('business.index')->with('success', 'Business approved successfully.');
    }

    // Reject a pending business account and notify its owner.
    public function reject($id)
    {
        $account = Business::findOrFail($id);
        $account->update(['status' => 'rejected']);

        $account->user->notify(new UserDatabaseNotification(
            'Business Account Rejected',
            'Your business account has been rejected.',
            ['type' => 'business_account', 'business_id' => $account->id]
        ));

        return redirect()->route('business.index')->with('success', 'Business rejected.');
    }

    // Alias for reject() — rejects a business account by ID.
    public function rejected($id)
    {
        return $this->reject($id);
    }

    // Delete the authenticated user's own business account.
    // If active paid rentals exist, soft-delete so renters keep access until rental_end_date.
    public function destroy(){
        $user = auth('users')->user();
        $business = $user->businesses()->pluck('id');
        $account = Business::whereIn('id', $business)->firstOrFail();

        $hasActiveRentals = ServiceRequest::where('business_id', $account->id)
            ->where('payment_status', 'paid')
            ->whereNotNull('rental_end_date')
            ->where('rental_end_date', '>', today())
            ->exists();

        if ($hasActiveRentals) {
            // Soft delete — services stay accessible to current renters until rental_end_date
            $account->delete();
        } else {
            // No active rentals — permanently remove everything
            $account->services()->each(fn($s) => $s->forceDelete());
            $account->forceDelete();
        }

        return response()->json([
            'status' => true,
            'message' => 'Business account deleted successfully.',
        ], 200);
    }
}
