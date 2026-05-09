<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Notifications\InvoiceCreated;
use App\Notifications\UserDatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{

//All services
  public function allservices(){
    $user = auth('users')->user();
    $business = $user->business()->pluck('id');
    $services = Service::with(['business', 'category'])
    ->where('status', 'approved')
    ->whereNotIn('business_id', $business)
    ->latest()
    ->get();
    return view('users.allservices',compact('services'));
    }

//user request service
public function requestservice(Request $request, $id)
{
    $user = auth('users')->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    $request->validate([
        'business_id' => 'required|exists:businesses,id',
        'quantity' => 'required|integer|min:1',
        'needed_at' => 'required|date|after:today',
        'details' => 'nullable|string',
    ]);

    $service = Service::with('business.user')->findOrFail($id);

    $myBusiness = Business::where('id', $request->business_id)
        ->where('user_id', $user->id)
        ->where('status', 'approved')
        ->first();

    if (!$myBusiness) {
        return response()->json([
            'message' => 'Business account not found or not approved.'
        ], 403);
    }

    if ($service->business_id == $myBusiness->id) {
        return response()->json([
            'message' => 'You cannot request your own service using the same business.'
        ], 403);
    }

    $servicerequest = ServiceRequest::create([
        'user_id' => $user->id,
        'business_id' => $myBusiness->id,
        'service_id' => $service->id,
        'quantity' => $request->quantity,
        'needed_at' => $request->needed_at,
        'details' => $request->details,
        'status' => 'pending',
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Service request submitted successfully.',
        'data' => $servicerequest,
    ], 201);
}

 //service sent
  public function sentservice(Request $request){
    $user =auth('users')->user();
      if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }
    $businesses = $user->businesses()->pluck('id');
    $service = ServiceRequest::with(['service.business', 'service.category','service.subcategory'])
    ->where('user_id', $user->id)
    ->whereNotIn('business_id', $businesses)

    ->latest()
    ->get();
    return response()->json([
      'message'=>'All services is sent',
      'data'=>$service,
    ]);
  }

//reseived service
  public function received(Request $request){
    $user = auth('users')->user();
    if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }
    $businesses = $user->businesses()->pluck('id');
    $service = ServiceRequest::with(['service.business', 'service.category','service.subcategory'])
    ->whereHas('service', function ($q) use ($businesses){
      $q->whereIn('business_id', $businesses)
      ->where('status', 'pending');
    })
    ->latest()
    ->get();
    return response()->json([
      'message'=>'All services is received',
      'data'=>$service,
    ]);
  }

 //incoming service
  public function incoming(){
    $user = auth('users')->user();
    $business = $user->businesses()->pluck('id');
    $request = ServiceRequest::with(['service.business', 'service.category','service.subcategory'])
    ->where('status', 'pendingq')
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })
    ->latest()
    ->get();
    return view('users.servicerequest',compact('request'));
  }


//Approve service
  public function approve($id){
    $user = auth('users')->user();
    $business = $user->businesses()->pluck('id');
    $request = ServiceRequest::with(['service.category','service.business','service.subcategory'])
    ->where('id', $id)
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })->firstOrFail();

    $request->update([
        'status' => 'approved',
    ]);
    $request->user->notify(new UserDatabaseNotification(
    'Service Approved',
    'Your service request has been approved.',
    [
        'type' => 'service',
        'service_id' => $request->service->id,
    ]
));

    return response()->json([
        'status' => true,
        'message' => 'Service request approved successfully.',
        'data' => $request,
    ]);
  }

//reject service
  public function reject($id){
    $user = auth('users')->user();
    $business = $user()->businesses()->pluck('id');
    $request = ServiceRequest::where('id', $id)
    ->where('status', 'pending')
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })->firstOrFail();

    $request->update([
        'status' => 'rejected',
    ]);
    $request->user->notify(new UserDatabaseNotification(
    'service Rejected',
    'Your service request has been rejected.',
    [
        'type' => 'service',
        'business_id' => $request->id,
    ]
));

    return response()->json([
        'status' => true,
        'message' => 'Service request rejected successfully.',
        'data' => $request,
    ]);
  }

//Approve service
  public function approverequest(){
    $user = auth('users')->user();
    $request = ServiceRequest::with(['service.category','service.business'])
    ->where('user_id', $user->id)
    ->where('status', 'approved')
    ->latest()
    ->get();
    $request->user->notify(new UserDatabaseNotification(
    'service Request ',
    'Your service request .',
    [
        'type' => 'service',
        'business_id' => $request->id,
    ]
));

    return view('users.servicereceived',compact('request'));
  }

 
}
