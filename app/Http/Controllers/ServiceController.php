<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
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
public function allser(Request $request){
    $services = Service::with(['business', 'category', 'subcategory'])
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
        ->latest()
        ->get();
    return view('super_admin.allservices', compact('services'));
}
public function myservice()
{
    $user = auth('users')->user();

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
public function allservices(){
  $user = auth('users')->user();
  $mybusinesses = $user->businesses()->pluck('id');
  $services = Service::with(['business', 'category','subcategory'])
  ->where('status', 'approved')
  ->whereNotIn('business_id',$mybusinesses)
  ->get();

  return response()->json([
        'status' => true,
        'data' => $services
    ], 200);


  /*$services = Service::with(['business', 'category', 'subcategory','city'])->where('status', 'approved')
//city filter
    ->where($request->city_id ,function($q) use ($request){
        $q->where('city_id',$request->city_id);
        })
//category filter
    ->where($request->category_id ,function($q) use ($request){
        $q->where('category_id',$request->category_id);
        })
//subcategory filter
    ->where($request->subcategory_id ,function($q) use ($request){
        $q->where('subcategory_id',$request->subcategory_id);
        })
//type filter
    ->where($request->services_type ,function($q) use ($request){
        $q->where('services_type',$request->services_type);
        })
//price filter
    ->where(function($q) use ($request){
        if ($request->price_min) {
            $q->where('price_usd', '>=', $request->price_min)
              ->orWhere('price_syp', '>=', $request->price_min);
        }
        if ($request->price_max) {
            $q->where('price_usd', '<=', $request->price_max)
              ->orWhere('price_syp', '<=', $request->price_max);
        }
    })
//name search
    ->where(function($q) use ($request){
        if ($request->search) {
            $q->where('title', 'like', '%' . $request->search . '%');
        }
    })
    ->get();
    return response()->json([
        'data' => $services,
        'message' => 'Services retrieved successfully',
    ]);*/
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
    ]);
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

    $val['status'] = 'pending';
    $service = Service::create($val);



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
        $account->update(['status' => 'approved']);
        return redirect()->route('allserviesad')->with('success', 'Service approved successfully.');
    }
    public function rejected($id){
        $account = Service::findOrFail($id);
        $account->update(['status' => 'rejected']);
        return redirect()->route('allserviesad')->with('success', 'Service rejected.');
    }

      public function pendingrec($id){
        $pending = Service::findOrFail($id)
        ->update(['status'=>'pending']);

        return redirect()->route('allservices',compact('pending'));
    }
    public function approverec($id){
        $user = auth('users')->user();

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
        $user = auth('users')->user();

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


    public function destroy($id){
        $service = Service::findOrFail($id);
        $service->delete();
        return redirect()->route('myservices');
    }

    public function edit($id){
        $service = Service::findOrFail($id);
        $businesses = Business::where('user_id', auth()->id())
        ->where('status', 'approved')
        ->get();
        $categories = Category::all();
        $subcategories = Subcategory::all();
        return view('users.serviceedit', compact('service', 'businesses', 'categories', 'subcategories'));
    }

    public function request(){
      $user = auth('users')->user();
      $businesses = $user->businesses()->pluck('id');
      $requests = Service::with(['business', 'category'])
      ->where('status', 'pending')
      ->whereNotIn('business_id', $businesses)
      ->latest()
      ->get();

      return view('users.servicerequest',compact('requests'));
}
}
