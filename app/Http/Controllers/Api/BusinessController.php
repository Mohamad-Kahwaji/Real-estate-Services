<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activetype;
use App\Models\Admin;
use App\Models\Business;
use App\Models\City;
use App\Models\Superadmin;
use App\Models\User;
use App\Notifications\UserDatabaseNotification;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // List businesses with optional search (job_name_ar, job_name_en) and filter by status, city_id.
    public function index(Request $request)
    {
        $term = $request->search ? '%' . $request->search . '%' : null;

        $businesses = Business::with(['user:id,name,phone', 'city:id,name_ar,name_en', 'activeType:id,name'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->city_id, fn($q) => $q->where('city_id', $request->city_id))
            ->when($term, fn($q) => $q->where(fn($q2) =>
                $q2->where('job_name_ar', 'like', $term)
                   ->orWhere('job_name_en', 'like', $term)
            ))
            ->latest()
            ->paginate(15);

        return response()->json([
            'status' => true,
            'data'   => $businesses,
        ]);
    }

    public function create()
    {
        return response()->json([
            'status' => true,
            'data' => [
                'activetypes' => Activetype::all(),
                'cities'      => City::all(),
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $val = $request->validate([
            'activetype_id' => 'required|exists:activetypes,id',
            'license_number' => 'required|integer',
            'job_name_ar' => 'required|string',
            'job_name_en' => 'required|string',
            'activites' => 'required|string',
            'details' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'image' => 'nullable|image',
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        $val['user_id'] = auth()->id();
        $val['status'] = 'pending';

        $exists = Business::where('user_id', auth()->id())
            ->where('activetype_id', $val['activetype_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($exists) {
            return response()->json([
                'status'  => false,
                'message' => 'You already have a business account with this activity type.',
            ], 422);
        }

        if ($request->hasFile('image')) {
          $val['image'] = $request->file('image')->store('businesses', 'public');
  }

        $business = Business::create($val);

        app(AdminPushNotificationService::class)->send(
            'New Business Account Request',
            'There is a new business account waiting for approval.',
            [
                'type' => 'business_account_request',
                'business_id' => $business->id,
            ]
        );

        $notification = new UserDatabaseNotification(
            'New Business Account Request',
            'User ' . ($business->user->name ?? '') . ' submitted a business account request.',
            ['type' => 'business_account_request', 'business_id' => $business->id]
        );
        Superadmin::all()->each(fn($sa) => $sa->notify($notification));
        Admin::all()->each(fn($a) => $a->notify($notification));

        return response()->json([
            'status' => true,
            'message' => 'Business account request submitted successfully. Please wait for approval.',
            'data' => $business
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $val = $request->validate([
            'user_id' => 'required|exists:users,id',
            'active' => 'required|boolean',
            'license_number' => 'required|integer',
            'job_name_ar' => 'required|string',
            'job_name_en' => 'required|string',
            'activites' => 'required|string',
            'details' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'image' => 'nullable|image',
        ]);

        $business = Business::findOrFail($id);
        $business->update($val);

        return response()->json([
            'status' => true,
            'message' => 'Business account updated successfully.',
            'data' => $business
        ], 200);
    }

    public function pending()
    {
        $accounts = Business::with(['user', 'city'])
            ->where('status', 'pending')
            ->get();

        return redirect()->route('',compact('accounts'));
    }

    public function approve($id)
    {
        $account = Business::findOrFail($id);

        $account->update([
            'status' => 'approved',
        ]);

        $account->user->notify(new UserDatabaseNotification(
            'Business Account Approved',
            'Your business account has been approved.',
            [
                'type' => 'business_account',
                'business_id' => $account->id,
            ]
        ));

        return redirect()->route('indexsuperadmin', response('accept'));
    }

    public function rejected($id){
      $account = Business::findOrFail($id);
      $account->update([ 'status'=>'rejected', ]);
      $account->user->notify(new UserDatabaseNotification(
        'Business Account Rejected',
        'Your business account has been rejected.',
        [ 'type' => 'business_account',
        'business_id' => $account->id, ] ));
        return redirect()->route('indexsuperadmin',response('rejected')); }
}
