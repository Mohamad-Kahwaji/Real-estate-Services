<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Service;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Submits or updates a report for a service, guards against self-reporting, and notifies admins via push.
    public function store(Request $request, $serviceId)
    {
        $user = auth('users')->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to report a service.');
        }

        $service = Service::with('business')->findOrFail($serviceId);

        if ($service->business && $service->business->user_id == $user->id) {
            return back()->with('error', 'You cannot report your own service.');
        }

        $request->validate([
            'reason'  => 'required|string|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        Report::updateOrCreate(
            ['user_id' => $user->id, 'service_id' => $service->id],
            ['reason' => $request->reason, 'message' => $request->message, 'status' => 'pending']
        );

        try {
            app(AdminPushNotificationService::class)->send(
                'New Service Report',
                'A user reported a service: ' . $service->title,
                ['type' => 'service_report', 'service_id' => $service->id]
            );
        } catch (\Throwable) {}

        return back()->with('success', 'Report submitted. We will review it shortly.');
    }

    // Lists all reports with optional search (service title, reason) and status filter.
    public function index(Request $request)
    {
        $term = $request->search ? '%' . $request->search . '%' : null;

        $reports = Report::with(['service.business', 'user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($term, fn($q) => $q->where(fn($q2) =>
                $q2->where('reason', 'like', $term)
                   ->orWhereHas('service', fn($s) => $s->where('title', 'like', $term))
            ))
            ->latest()
            ->get();

        if ($request->expectsJson()) {
            return response()->json(['status' => true, 'data' => $reports]);
        }

        return view('super_admin.reports', compact('reports'));
    }

    // Updates the review status of a report (pending, reviewed, or resolved).
    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $request->validate(['status' => ['required', 'in:pending,reviewed,resolved']]);
        $report->update(['status' => $request->status]);
        return back()->with('success', 'Report status updated.');
    }

    // Permanently deletes a report by ID.
    public function destroy($id)
    {
        Report::findOrFail($id)->delete();
        return back()->with('success', 'Report deleted.');
    }
}
