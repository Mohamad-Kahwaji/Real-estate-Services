<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Notifications\UserDatabaseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceRequestController extends Controller
{

//All services
  public function allservices(Request $request){
    $user = $request->user();
    $business = $user->businesses()->pluck('id');
    $services = Service::with(['business', 'category'])
    ->where('status', 'approved')
    ->whereIn('business_id', $business)
    ->latest()
    ->get();
    return view('users.allservices',compact('services'));
    }

//user request service
public function requestservice(Request $request, $id)
{
    $user = $request->user();

    $request->validate([
        'quantity'  => 'required|integer|min:1',
        'needed_at' => 'required|date|after:today',
        'details'   => 'nullable|string',
    ]);

    // Requesting user must have an approved business account
    $myBusiness = $user->businesses()->where('status', 'approved')->first();
    if (!$myBusiness) {
        return response()->json([
            'status'  => false,
            'message' => 'You need an approved business account to request services.',
        ], 403);
    }

    $service = Service::with('business.user')->findOrFail($id);

    if ($service->status !== 'approved') {
        return response()->json([
            'status'  => false,
            'message' => 'This service is not currently available.',
        ], 422);
    }

    // Prevent requesting a service that belongs to own business
    if ($service->business_id === $myBusiness->id) {
        return response()->json([
            'status'  => false,
            'message' => 'You cannot request your own business service.',
        ], 403);
    }

    if ($request->quantity > $service->quantity) {
        return response()->json([
            'status'  => false,
            'message' => 'Requested quantity exceeds available stock. Available: ' . $service->quantity,
        ], 422);
    }

    $serviceRequest = ServiceRequest::create([
        'user_id'        => $user->id,
        'business_id'    => $service->business_id,
        'service_id'     => $service->id,
        'quantity'       => $request->quantity,
        'needed_at'      => $request->needed_at,
        'details'        => $request->details,
        'status'         => 'pending',
        'payment_status' => 'unpaid',
    ]);

    $serviceOwner = $service->business->user;
    if ($serviceOwner) {
        $serviceOwner->notify(new UserDatabaseNotification(
            'New Service Request',
            'You have received a new request for your service: ' . $service->title,
            [
                'type'               => 'service_request',
                'service_request_id' => $serviceRequest->id,
                'service_id'         => $service->id,
            ]
        ));
    }

    return response()->json([
        'status'  => true,
        'message' => 'Service request submitted. Proceed to payment.',
        'data'    => $serviceRequest,
    ], 201);
}

 //approved services received by user
  public function approvedservices(Request $request){
    $user = $request->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    $businesses = $user->businesses()->pluck('id');
    $services = ServiceRequest::with(['service.business', 'service.category', 'service.subcategory'])
    ->where('user_id', $user->id)
    ->where('status', 'approved')
    ->whereHas('service', function ($q) use ($businesses) {
        $q->whereNotIn('business_id', $businesses);
    })
    ->latest()
    ->get();

    $services->each(fn($sr) => $sr->service->makeHidden('quantity'));

    return response()->json([
        'message' => 'All approved services',
        'data'    => $services,
    ]);
  }

 //service sent
  public function sentservice(Request $request){
    $user = $request->user();
      if (!$user) {
        return response()->json([
            'message' => 'Unauthenticated.'
        ], 401);
    }
    $businesses = $user->businesses()->pluck('id');
    $service = ServiceRequest::with(['service.business', 'service.category', 'service.subcategory', 'payment'])
    ->where('user_id', $user->id)
    ->where('status', 'pending')
    ->whereHas('service', function ($q) use ($businesses) {
        $q->whereNotIn('business_id', $businesses);
    })
    ->latest()
    ->get();
    return response()->json([
      'message' => 'All services is sent',
      'data'    => $service,
    ]);
  }

//completed services (approved requests on my services - both rent and sale)
  public function completedservices(Request $request){
    $user = $request->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    $businesses = $user->businesses()->pluck('id');
    $requests = ServiceRequest::with([
        'service.category',
        'service.subcategory',
        'user',
        'business',
    ])
    ->where('status', 'approved')
    ->whereHas('service', function ($q) use ($businesses) {
        $q->whereIn('business_id', $businesses);
    })
    ->latest()
    ->get();

    $requests->each(fn($sr) => $sr->service->makeHidden('quantity'));

    return response()->json([
        'message' => 'All sold services',
        'data'    => $requests,
    ]);
  }

//received service — only show paid requests to the service owner
  public function received(Request $request){
    $user = $request->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
    $businesses = $user->businesses()->pluck('id');
    $requests = ServiceRequest::with([
        'service.category',
        'service.subcategory',
        'user',
        'business',
        'payment',
    ])
    ->where('status', 'pending')
    ->where('payment_status', 'paid')
    ->whereHas('service', function ($q) use ($businesses){
        $q->whereIn('business_id', $businesses);
    })
    ->latest()
    ->get();
    return response()->json([
      'message' => 'All received service requests',
      'data'    => $requests,
    ]);
  }

 //incoming service
  public function incoming(Request $request){
    $user = $request->user();
    $business = $user->businesses()->pluck('id');
    $serviceRequests = ServiceRequest::with(['service.business', 'service.category','service.subcategory'])
    ->where('status', 'pending')
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })
    ->latest()
    ->get();
    return view('users.servicerequest',compact('serviceRequests'));
  }


//Approve service — requires payment_status = paid
  public function approve(Request $request, $id){
    $user = $request->user();
    $business = $user->businesses()->pluck('id');
    $serviceRequest = ServiceRequest::with(['service.category','service.business','service.subcategory'])
    ->where('id', $id)
    ->where('payment_status', 'paid')
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })->firstOrFail();

    $service = $serviceRequest->service;

    if ($service->quantity < $serviceRequest->quantity) {
        return response()->json([
            'status' => false,
            'message' => 'Not enough quantity available. Available: ' . $service->quantity,
        ], 422);
    }

    DB::transaction(function () use ($serviceRequest, $service) {
        $serviceRequest->update(['status' => 'approved']);
        $newQuantity = $service->quantity - $serviceRequest->quantity;
        $service->update([
            'quantity' => $newQuantity,
            'status' => $newQuantity > 0 ? 'approved' : 'pending',
        ]);
    });

    $serviceRequest->user->notify(new UserDatabaseNotification(
        'Service Approved',
        'Your service request has been approved.',
        [
            'type' => 'service',
            'service_id' => $service->id,
        ]
    ));

    return response()->json([
        'status' => true,
        'message' => 'Service request approved successfully.',
        'data' => $serviceRequest,
    ]);
  }

//reject service
  public function reject(Request $request, $id){
    $user = $request->user();
    $business = $user->businesses()->pluck('id');
    $serviceRequest = ServiceRequest::where('id', $id)
    ->where('status', 'pending')
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })->firstOrFail();

    DB::transaction(function () use ($serviceRequest) {
        $serviceRequest->update(['status' => 'rejected']);
        $serviceRequest->service->update(['status' => 'approved']);
    });

    $serviceRequest->user->notify(new UserDatabaseNotification(
    'Service Rejected',
    'Your service request has been rejected.',
    [
        'type' => 'service',
        'service_id' => $serviceRequest->service_id,
    ]
));

    return response()->json([
        'status' => true,
        'message' => 'Service request rejected successfully.',
        'data' => $serviceRequest,
    ]);
  }

}