<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Service;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
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

    public function index()
    {
        $reports = Report::with(['service.business', 'user'])->latest()->get();
        return view('super_admin.reports', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $request->validate(['status' => ['required', 'in:pending,reviewed,resolved']]);
        $report->update(['status' => $request->status]);
        return back()->with('success', 'Report status updated.');
    }

    public function destroy($id)
    {
        Report::findOrFail($id)->delete();
        return back()->with('success', 'Report deleted.');
    }
}
