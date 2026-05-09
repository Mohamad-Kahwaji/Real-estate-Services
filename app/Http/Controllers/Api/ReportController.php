<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Service;
use App\Notifications\InvoiceCreated;
use App\Notifications\UserDatabaseNotification;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request, $service)
    {
      $user =  auth('users')->user();
      if (!$user) {
        return redirect()->route('login')->with('error', 'You must be logged in to report a service.');
      }
      if ($service->business && $service->business->user_id == $user->id) {
    return back()->with('error', 'You cannot report your own service.');
}

      $service = Service::findOrFail($service);

      $request->validate([
            'reason' => 'required|string|max:255',
            'message' => 'nullable|string|max:2000',
        ]);
        $report = Report::updateOrCreate(
            ['user_id' => $user->id,
            'service_id' => $service->id,
            ],
            [
                'reason' => $request->reason,
                'message' => $request->message,
                'status' => 'pending',
            ]
            );

      app(AdminPushNotificationService::class)->send(
    'New Service Report',
    'A user reported a service.',
    [
        'type' => 'service_report',
        'report_id' => $report->id,
        'service_id' => $report->service_id,
    ]
);

    $request->user->notify(new InvoiceCreated(
    'Report created',
    'Your Report.',
    [
        'type' => 'report',
        'business_id' => $request->id,
    ]
    ));
        return back()->with('success', 'Report submitted successfully.');
    }



    public function index(){
        $reports = Report::with(['service.business','user'])->get();
        return view('super_admin.reports',compact('reports'));
    }
    public function updateStatus(Request $request, $id)
{
    $report = Report::findOrFail($id);

    $request->validate([
        'status' => ['required', 'in:pending,reviewed,resolved']
    ]);

    $report->update([
        'status' => $request->status
    ]);

    return back()->with('success', 'Report status updated successfully.');
}
public function destroy($id)
{
    $report = Report::findOrFail($id);
    $report->delete();

    return back()->with('success', 'Report deleted successfully.');
}
}
