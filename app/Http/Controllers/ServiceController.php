<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Business;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Subcategory;
use App\Models\Superadmin;
use App\Notifications\UserDatabaseNotification;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;
use Termwind\Components\Raw;

class ServiceController extends Controller
{
    // Lists all services for the superadmin with filtering by status, category, subcategory, type, city, and price.
    public function allser(Request $request){
        $term = $request->search ? '%' . $request->search . '%' : null;

        $query = Service::with(['business.city', 'category', 'subcategory'])
            ->withAvg('review', 'rating')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
            ->when($request->subcategory_id, fn($q) => $q->where('subcategory_id', $request->subcategory_id))
            ->when($request->services_type, fn($q) => $q->where('services_type', $request->services_type))
            ->when($request->city_id, fn($q) => $q->whereHas('business', fn($b) => $b->where('city_id', $request->city_id)))
            ->when($request->price_min, fn($q) => $q->where('price_usd', '>=', $request->price_min))
            ->when($request->price_max, fn($q) => $q->where('price_usd', '<=', $request->price_max))
            ->when($term, fn($q) => $q->where(fn($q2) =>
                $q2->where('title', 'like', $term)->orWhere('description', 'like', $term)
            ));

        $services = $query->latest()->paginate(15)->withQueryString();

        // fuzzy fallback — search word by word
        if ($services->isEmpty() && $request->search) {
            $words = array_filter(explode(' ', $request->search));
            $services = Service::with(['business.city', 'category', 'subcategory'])
                ->withAvg('review', 'rating')
                ->where(function($q) use ($words) {
                    foreach ($words as $word) {
                        $q->orWhere('title', 'like', '%' . $word . '%');
                    }
                })
                ->latest()->paginate(15)->withQueryString();
        }

        $cities        = \App\Models\City::orderBy('name_ar')->get();
        $categories    = \App\Models\Category::orderBy('name_ar')->get();
        $subcategories = \App\Models\Subcategory::orderBy('name_ar')->get();

        return view('super_admin.allservices', compact('services', 'cities', 'categories', 'subcategories'));
    }

    // Returns all services belonging to the authenticated user's businesses as JSON.
    public function myservice()
    {
        $user = auth('users')->user();

        if (!$user) {
            return response()->json([
                'status'  => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $businessIds = $user->businesses()->pluck('id');

        if ($businessIds->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No business account found.'
            ], 404);
        }

        $services = Service::whereIn('business_id', $businessIds)->get();

        return response()->json([
            'status' => true,
            'data'   => $services
        ], 200);
    }

    // Placeholder for outgoing service requests (not yet implemented).
    public function sent(){

    }

    // Placeholder for received service requests (not yet implemented).
    public function recevied(){

    }

    // Returns all approved services that do not belong to the authenticated user's own businesses.
    public function allservices(){
        $user         = auth('users')->user();
        $mybusinesses = $user->businesses()->pluck('id');
        $services     = Service::with(['business', 'category', 'subcategory'])
            ->where('status', 'approved')
            ->whereNotIn('business_id', $mybusinesses)
            ->get();

        return response()->json([
            'status' => true,
            'data'   => $services
        ], 200);
    }

    // Loads the service creation form with the user's approved businesses, categories, and subcategories.
    public function create(){
        $businesses    = Business::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->get();
        $categories    = Category::all();
        $subcategories = Subcategory::all();

        return view('users.servicecreate', compact('businesses', 'categories', 'subcategories'));
    }

    // Validates, creates a new service listing, and notifies the user and all superadmins for approval.
    public function store(Request $request){
        $val = $request->validate([
            'business_id'    => 'required|exists:businesses,id',
            'category_id'    => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'title'          => 'required|string',
            'description'    => 'required|string',
            'quantity'       => 'required|integer',
            'services_type'  => 'required|string',
            'price_usd'      => 'required|numeric',
            'price_syp'      => 'required|numeric',
            'image'          => 'nullable|image',
            'latitude'       => ['nullable', 'numeric'],
            'longitude'      => ['nullable', 'numeric'],
        ]);

        $business = Business::where('id', $val['business_id'])
            ->where('user_id', auth()->id())
            ->first();

        if (!$business) {
            return back()->withErrors([
                'business_id' => 'Business account not found.',
            ])->withInput();
        }

        if ($business->status !== 'approved') {
            return back()->withErrors([
                'business_id' => 'You can add a service only if the business account is approved.',
            ])->withInput();
        }

        if ($request->hasFile('image')) {
            $val['image'] = $request->file('image')->store('services', 'public');
        }

        $val['status'] = 'pending';
        $service = Service::create($val);

        $service->user->notify(new UserDatabaseNotification(
            'Service Created',
            'Your service has been created and is pending approval.',
            ['type' => 'service', 'service_id' => $service->id]
        ));

        app(AdminPushNotificationService::class)->send(
            'New Service Request',
            'A new service is waiting for approval.',
            ['type' => 'service_request', 'service_id' => $service->id]
        );

        $adminNotification = new UserDatabaseNotification(
            'New Service Request',
            'User ' . ($service->user->name ?? '') . ' submitted a new service for approval.',
            ['type' => 'service_request', 'service_id' => $service->id]
        );
        Superadmin::all()->each(fn($sa) => $sa->notify($adminNotification));
        Admin::all()->each(fn($a) => $a->notify($adminNotification));

        return response()->json([
            'status'  => true,
            'message' => 'Service created successfully and is pending approval.',
            'data'    => $service
        ], 201);
    }

    // Sets a service back to pending status (superadmin action).
    public function pending($id){
        $pending = Service::findOrFail($id)->update(['status' => 'pending']);
        return redirect()->route('indexsuperadmin', compact('pending'));
    }

    // Approves a service listing (superadmin action).
    public function approve($id){
        $account = Service::findOrFail($id);
        $account->update(['status' => 'approved']);
        return redirect()->route('allserviesad')->with('success', 'Service approved successfully.');
    }

    // Rejects a service listing (superadmin action).
    public function rejected($id){
        $account = Service::findOrFail($id);
        $account->update(['status' => 'rejected']);
        return redirect()->route('allserviesad')->with('success', 'Service rejected.');
    }

    // Sets a received service request back to pending (business-owner action).
    public function pendingrec($id){
        $pending = Service::findOrFail($id)->update(['status' => 'pending']);
        return redirect()->route('allservices', compact('pending'));
    }

    // Approves a service request directed at the authenticated user's business.
    public function approverec($id){
        $user = auth('users')->user();

        if (!$user->businesses) {
            return back()->with('error', 'No business account found.');
        }

        $businesses = $user->businesses()->pluck('id');
        $request = ServiceRequest::with(['service.category', 'service.business'])
            ->where('id', $id)
            ->whereHas('service', function ($q) use ($businesses){
                $q->whereIn('business_id', $businesses);
            })
            ->where('status', 'pending')
            ->first();

        if (!$request) {
            return back()->with('error', 'Request not found or not allowed.');
        }

        $request->update(['status' => 'approved']);
        return redirect()->route('approveserrec', response('accept'));
    }

    // Rejects a service request directed at the authenticated user's business.
    public function rejectedrec($id){
        $user = auth('users')->user();

        if (!$user || !$user->business) {
            return back()->with('error', 'No business account found.');
        }

        $request = ServiceRequest::where('id', $id)
            ->where('business_id', $user->business->id)
            ->where('status', 'pending')
            ->first();

        if (!$request) {
            return back()->with('error', 'Request not found.');
        }

        $request->update(['status' => 'rejected']);
        return redirect()->route('rejectserrec', response('rejected'));
    }

    // Sets one of the user's own services back to pending status.
    public function pendingmy($id){
        $pending = Service::findOrFail($id)->update(['status' => 'pending']);
        return redirect()->route('pendingsermy', compact('pending'));
    }

    // Marks one of the user's own services as approved.
    public function approvemy($id){
        $account = Service::findOrFail($id);
        $account->update(['status' => 'approved']);
        return redirect()->route('approvesermy', response('accept'));
    }

    // Marks one of the user's own services as rejected.
    public function rejectedmy($id){
        $account = Service::findOrFail($id);
        $account->update(['status' => 'rejected']);
        return redirect()->route('rejectsermy', response('rejected'));
    }

    // Deletes a service. Soft-deletes if active paid rentals exist; force-deletes otherwise.
    public function destroy($id){
        $service = Service::findOrFail($id);

        $hasActiveRentals = ServiceRequest::where('service_id', $service->id)
            ->where('payment_status', 'paid')
            ->whereNotNull('rental_end_date')
            ->where('rental_end_date', '>', today())
            ->exists();

        if ($hasActiveRentals) {
            $service->delete();
        } else {
            $service->forceDelete();
        }

        return redirect()->route('myservices');
    }

    // Loads the service edit form for the given service with businesses, categories, and subcategories.
    public function edit($id){
        $service       = Service::findOrFail($id);
        $businesses    = Business::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->get();
        $categories    = Category::all();
        $subcategories = Subcategory::all();
        return view('users.serviceedit', compact('service', 'businesses', 'categories', 'subcategories'));
    }

    // Lists pending services from other businesses that the user can browse as requests.
    public function request(){
        $user       = auth('users')->user();
        $businesses = $user->businesses()->pluck('id');
        $requests   = Service::with(['business', 'category'])
            ->where('status', 'pending')
            ->whereNotIn('business_id', $businesses)
            ->latest()
            ->get();

        return view('users.servicerequest', compact('requests'));
    }
}
