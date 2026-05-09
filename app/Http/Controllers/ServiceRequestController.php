<?php

namespace App\Http\Controllers;

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

//request service
  public function requestservice(Request $request, $id){
    $user = auth('users')->user();
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'needed_at' => 'required|date|after:today',
        'details' => 'nullable|string',
    ]);
    $servicerequest = ServiceRequest::create([
        'user_id' => $user->id,
        'service_id' => $id,
        'business_id' => Service::findOrFail($id)->business_id,
        'quantity' => $request->quantity,
        'needed_at' => $request->needed_at,
        'details' => $request->details,
        'status' => 'pending',
    ]);
    return back()->with('success', 'Service request submitted successfully.');
  }


 //service sent
  public function sentservice(){
    $user = auth('users')->user();
    $requests = ServiceRequest::with(['service.business', 'service.category'])
    ->where('user_id', $user->id)
    ->latest()
    ->get();
    return view('users.sentservice',compact('requests'));
  }

 //incoming service
  public function incoming(){
    $user = auth('users')->user();
    $business = $user->businesses()->pluck('id');
    $request = ServiceRequest::with(['service.business', 'service.category'])
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
    $request = ServiceRequest::with(['service.category','service.business'])
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

    return redirect()->route('servicerequest');
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

    return redirect()->route('servicerequest');
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
