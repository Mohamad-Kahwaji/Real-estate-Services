<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Service;
use App\Notifications\UserDatabaseNotification;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // Submits or updates a report for a service, guarding against self-reporting, and notifies admins.
    public function store(Request $request, $service)
    {
        $user = auth('users')->user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'You must be logged in to report a service.');
        }

        $service = Service::with('business')->findOrFail($service);

        if ($service->business && $service->business->user_id == $user->id) {
            return back()->with('error', 'You cannot report your own service.');
        }

        $request->validate([
            'reason'  => 'required|string|max:255',
            'message' => 'nullable|string|max:2000',
        ]);

        $report = Report::updateOrCreate(
            ['user_id' => $user->id, 'service_id' => $service->id],
            ['reason' => $request->reason, 'message' => $request->message, 'status' => 'pending']
        );

        app(AdminPushNotificationService::class)->send(
            'New Service Report',
            'A user reported a service.',
            ['type' => 'service_report', 'report_id' => $report->id, 'service_id' => $report->service_id]
        );

        return back()->with('success', 'Report submitted successfully.');
    }

    // Lists all reports with their related service, business, and submitting user for the admin panel.
    public function index(){
        $reports = Report::with(['service.business', 'user'])->get();
        return view('super_admin.reports', compact('reports'));
    }

    // Updates the review status of a report (pending, reviewed, or resolved).
    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $request->validate(['status' => ['required', 'in:pending,reviewed,resolved']]);
        $report->update(['status' => $request->status]);
        return back()->with('success', 'Report status updated successfully.');
    }

    // Permanently deletes a report by ID.
    public function destroy($id)
    {
        $report = Report::findOrFail($id);
        $report->delete();
        return back()->with('success', 'Report deleted successfully.');
    }
}
