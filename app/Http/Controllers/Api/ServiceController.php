<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceFieldValue;
use App\Models\ServiceRequest;
use App\Models\Subcategory;
use App\Models\Superadmin;
use App\Notifications\InvoiceCreated;
use App\Notifications\UserDatabaseNotification;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;
use Termwind\Components\Raw;

class ServiceController extends Controller
{
    // Returns a single approved service by ID with its category, subcategory, and business details.
    public function show($id)
    {
        $service = Service::with(['business.city', 'category', 'subcategory'])
            ->where('status', 'approved')
            ->findOrFail($id);

        return response()->json([
            'status' => true,
            'data'   => $service,
        ]);
    }

public function myservice()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Unauthenticated.'
        ], 401);
    }

    $businessIds = $user->businesses()->pluck('id');

    if ($businessIds->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No business account found.'
        ], 404);
    }

    $services = Service::whereIn('business_id', $businessIds)->get();

    return response()->json([
        'status' => true,
        'data' => $services
    ], 200);
}
public function sent(){

}
public function recevied(){

}
public function allservices(Request $request){
    $user = auth()->user();
    $mybusinesses = $user->businesses()->pluck('id');

    $services = Service::with(['business', 'category', 'subcategory', 'fieldValues.dynamicField'])
        ->where('status', 'approved')
        ->whereNotIn('business_id', $mybusinesses)
        ->when($request->city_id, fn($q) => $q->whereHas('business', fn($b) => $b->where('city_id', $request->city_id)))
        ->when($request->category_id, fn($q) => $q->where('category_id', $request->category_id))
        ->when($request->subcategory_id, fn($q) => $q->where('subcategory_id', $request->subcategory_id))
        ->when($request->services_type, fn($q) => $q->where('services_type', $request->services_type))
        ->when($request->price_min, fn($q) => $q->where('price_usd', '>=', $request->price_min))
        ->when($request->price_max, fn($q) => $q->where('price_usd', '<=', $request->price_max))
        ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
        ->latest()
        ->get();

    return response()->json([
        'status' => true,
        'data' => $services,
    ], 200);
}
  public function create(){
    $businesses = Business::where('user_id', auth()->id())
        ->where('status', 'approved')
        ->get();
    $categories = Category::all();
    $subcategories = Subcategory::all();


    return view('users.servicecreate',compact('businesses','categories','subcategories')  );


  }
  public function store(Request $request){

    $val = $request->validate([
        'business_id'=>'required|exists:businesses,id',
        'category_id'=>'required|exists:categories,id',
        'subcategory_id'=>'required|exists:subcategories,id',
        'title'=>'required|string',
        'description'=>'required|string',
        'quantity'=>'required|integer',
        'services_type'=>'required|string',
        'price_usd'=>'required|numeric',
        'price_syp'=>'required|numeric',
        'image'=>'nullable|image',
        'latitude' => ['nullable', 'numeric'],
        'longitude' => ['nullable', 'numeric'],
///
        'dynamic_field_id' => 'nullable|array',

///
        'dynamic_values' => 'nullable|array',
        'dynamic_values.*.field_id' => 'required|exists:dynamic_fields,id',
        'dynamic_values.*.value' => 'nullable',
    ]);
    $user = $request->user();

      if (!$user) {
          return response()->json([
              'message' => 'Unauthenticated.'
          ], 401);
      }
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

  $dynamicValues = $val['dynamic_values'] ?? [];
    unset($val['dynamic_values']);

    $val['status'] = 'pending';
    $service = Service::create($val);

    foreach ($request->dynamic_values ?? [] as $field) {
        ServiceFieldValue::create([
            'service_id' => $service->id,
            'dynamic_field_id' => $field['field_id'],
            'value' => $field['value'] ?? null,
        ]);
    }

    $service->user->notify(new InvoiceCreated(
        'Service Created',
        'Your service has been created and is pending approval.',
        [
            'type' => 'service',
            'service_id' => $service->id,
        ]
    ));

    app(AdminPushNotificationService::class)->send(
        'New Service Request',
        'A new service is waiting for approval.',
        [
            'type' => 'service_request',
            'service_id' => $service->id,
        ]
    );

    $adminNotification = new UserDatabaseNotification(
        'New Service Request',
        'User ' . ($service->user->name ?? '') . ' submitted a new service for approval.',
        ['type' => 'service_request', 'service_id' => $service->id]
    );
    Superadmin::all()->each(fn($sa) => $sa->notify($adminNotification));

    return response()->json([
        'status' => true,
        'message' => 'Service created successfully and is pending approval.',
        'data' => $service
    ], 201);

  }

  public function pending($id){
        $pending = Service::findOrFail($id)
        ->update(['status'=>'pending']);

        return redirect()->route('indexsuperadmin',compact('pending'));
    }
    public function approve($id){
        $account = Service::findOrFail($id);
        $account->Update([
            'status'=>'approved',
        ]);
        return redirect()->route('indexsuperadmin',response('accept'));
    }
    public function rejected($id){
        $account = Service::findOrFail($id);
        $account->Update([
            'status'=>'rejected',
        ]);
        return redirect()->route('indexsuperadmin',response('rejected'));
    }

      public function pendingrec($id){
        $pending = Service::findOrFail($id)
        ->update(['status'=>'pending']);

        return redirect()->route('allservices',compact('pending'));
    }
    public function approverec($id){
        $user = auth()->user();

    if (!$user->businesses) {
        return back()->with('error', 'No business account found.');
    }
    $businesses = $user->businesses()->pluck('id');
    $request = ServiceRequest::with(['service.category','service.business'])
      ->where('id', $id)
    ->whereHas('service', function ($q) use ($businesses){
      $q->whereIn('business_id', $businesses);
    })
        ->where('status', 'pending')
        ->first();
if (!$request) {
        return back()->with('error', 'Request not found or not allowed.');
    }
    $request->update([
        'status' => 'approved',
    ]);
        return redirect()->route('approveserrec',response('accept'));
    }
    public function rejectedrec($id){
        $user = auth()->user();

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

    $request->update([
        'status' => 'rejected',
    ]);
        return redirect()->route('rejectserrec',response('rejected'));
    }


      public function pendingmy($id){
        $pending = Service::findOrFail($id)
        ->update(['status'=>'pending']);

        return redirect()->route('pendingsermy',compact('pending'));
    }
    public function approvemy($id){
        $account = Service::findOrFail($id);
        $account->Update([
            'status'=>'approved',
        ]);
        return redirect()->route('approvesermy',response('accept'));
    }
    public function rejectedmy($id){
        $account = Service::findOrFail($id);
        $account->Update([
            'status'=>'rejected',
        ]);
        return redirect()->route('rejectsermy',response('rejected'));
    }




    public function editservice($id)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    $service = Service::with([
            'business',
            'category',
            'subcategory',
            'fieldValues.dynamicField'
        ])
        ->where('id', $id)
        ->whereHas('business', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
        ->firstOrFail();

    $businesses = Business::where('user_id', $user->id)
        ->where('status', 'approved')
        ->get();

    $categories = Category::all();

    $subcategories = Subcategory::where('category_id', $service->category_id)->get();

    return response()->json([
        'status' => true,
        'data' => [
            'service' => $service,
            'businesses' => $businesses,
            'categories' => $categories,
            'subcategories' => $subcategories,
        ]
    ]);
}



  public function destroy($id){
    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $business = $user->businesses()->pluck('id');
    $request = ServiceRequest::where('id', $id)
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })->firstOrFail();

    $request->delete();

    return response()->json([
        'status' => true,
        'message' => 'Service request deleted successfully.',
    ]);
}
    public function stopmyservice($id){
    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }
    $business = $user->businesses()->pluck('id');
    $request = ServiceRequest::where('id', $id)
    ->whereHas('service', function ($q) use ($business){
      $q->whereIn('business_id', $business);
    })->firstOrFail();
    $request->update([
        'status' => 'stopped',
    ]);
}


}


