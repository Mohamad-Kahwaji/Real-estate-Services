<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Notifications\UserDatabaseNotification;
use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{
    // All services available to request
    public function allservices()
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $services = Service::with(['business.user', 'business.activeType', 'business.city', 'category'])
            ->where('status', 'approved')
            ->whereNotIn('business_id', $businessIds)
            ->latest()
            ->get();

        $favorites = $user->favorites()->pluck('service_id')->toArray();

        return view('users.allservice', compact('services', 'favorites'));
    }

    // Create a new service request → redirect to payment
    public function requestservice(Request $request, $id)
    {
        $user = auth('users')->user();
        $request->validate([
            'quantity'  => 'required|integer|min:1',
            'needed_at' => 'required|date|after:today',
            'details'   => 'nullable|string',
        ]);

        $myBusiness = $user->businesses()->where('status', 'approved')->first();
        if (!$myBusiness) {
            return back()->with('error', 'You need an approved business account to request services.');
        }

        $service = Service::findOrFail($id);

        if ($service->business_id === $myBusiness->id) {
            return back()->with('error', 'You cannot request your own business service.');
        }

        $serviceRequest = ServiceRequest::create([
            'user_id'        => $user->id,
            'service_id'     => $id,
            'business_id'    => Service::findOrFail($id)->business_id,
            'quantity'       => $request->quantity,
            'needed_at'      => $request->needed_at,
            'details'        => $request->details,
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('payment.checkout', $serviceRequest->id);
    }

    // Sent requests (by current user)
    public function sentservice()
    {
        /** @var User $user */
        $user     = auth('users')->user();
        $requests = ServiceRequest::with(['service.business', 'service.category', 'payment'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('users.sentservice', compact('requests'));
    }

    // Incoming requests for business owner — only paid ones
    public function incoming()
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $requests = ServiceRequest::with(['service.business', 'service.category', 'payment'])
            ->where('status', 'pending')
            ->where('payment_status', 'paid')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->latest()
            ->get();

        return view('users.servicerequest', compact('requests'));
    }

    // Approve a service request (only if payment_status = paid)
    public function approve($id)
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $serviceRequest = ServiceRequest::with(['service.category', 'service.business'])
            ->where('id', $id)
            ->where('payment_status', 'paid')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->firstOrFail();

        $serviceRequest->update(['status' => 'approved']);

        $serviceRequest->user->notify(new UserDatabaseNotification(
            'Service Approved',
            'Your service request has been approved.',
            ['type' => 'service', 'service_id' => $serviceRequest->service_id]
        ));

        return redirect()->route('incoming.user')
            ->with('success', 'Request approved successfully.');
    }

    // Reject a service request
    public function reject($id)
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $serviceRequest = ServiceRequest::where('id', $id)
            ->where('status', 'pending')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->firstOrFail();

        $serviceRequest->update(['status' => 'rejected']);

        $serviceRequest->user->notify(new UserDatabaseNotification(
            'Service Rejected',
            'Your service request has been rejected.',
            ['type' => 'service', 'service_id' => $serviceRequest->service_id]
        ));

        return redirect()->route('incoming.user')
            ->with('success', 'Request rejected.');
    }

    // Approved requests received (for service owner)
    public function received()
    {
        /** @var User $user */
        $user        = auth('users')->user();
        $businessIds = $user->businesses()->pluck('id');

        $requests = ServiceRequest::with(['service.category', 'service.business', 'user', 'payment'])
            ->where('status', 'approved')
            ->whereHas('service', fn ($q) => $q->whereIn('business_id', $businessIds))
            ->latest()
            ->get();

        return view('users.servicereceived', compact('requests'));
    }

    // Approved service requests sent by this user
    public function approverequest()
    {
        /** @var User $user */
        $user     = auth('users')->user();
        $requests = ServiceRequest::with(['service.category', 'service.business', 'payment'])
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('users.servicereceived', compact('requests'));
    }

    // Admin: approve a received service request
    public function approverec($id)
    {
        $serviceRequest = ServiceRequest::where('id', $id)
            ->where('payment_status', 'paid')
            ->firstOrFail();

        $serviceRequest->update(['status' => 'approved']);

        $serviceRequest->user->notify(new UserDatabaseNotification(
            'Service Approved',
            'Your service request has been approved.',
            ['type' => 'service', 'service_id' => $serviceRequest->service_id]
        ));

        return back()->with('success', 'Request approved.');
    }
}
