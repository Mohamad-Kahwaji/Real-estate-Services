<?php
use  App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::get('send', [FcmController::class, 'send']);

//notification routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);
});

//report
    Route::post('report/{id}',[ReportController::class,'store'])->name('report.store');
    Route::get('reports',[ReportController::class,'index'])->name('reports.index');//->middleware('auth:superadmins');
    Route::post('updatereport/{id}',[ReportController::class,'updateStatus'])->name('report.updateStatus');//->middleware('auth:superadmins');
    Route::delete('deletereport/{id}',[ReportController::class,'destroy'])->name('report.destroy');//->middleware('auth:superadmins');

//favorite
    Route::post('/favorite/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

//reviews
    Route::resource('reviews', ReviewController::class);

//business
    Route::resource('business',BusinessController::class)->middleware('auth.basic:users,phone');

//users
    Route::resource('allindex',UserController::class);
    Route::put('pu',[UserController::class,'edit'])->middleware('auth:users');
    Route::put('pud',[UserController::class,'update'])->middleware('auth:users');

//services
    Route::get('allservices',[ServiceController::class,'allservices'])->name('allservices')->middleware('auth.basic:users,phone');
    Route::get('myservices',[ServiceController::class,'myservice'])->name('myservices')->middleware('auth.basic:users,phone');//->middleware('permission:view services,users');
    Route::resource('services',ServiceController::class);
    Route::post('service',[ServiceController::class,'store'])->name('service.store')->middleware('auth.basic:users,phone');

//reqests services
    Route::get('servicereceived',[ServiceRequestController::class,'received'])->middleware('auth.basic:users,phone');//->middleware('permission:view orders received,users');

//sent services
    Route::get('sentservice',[ServiceRequestController::class,'sentservice'])->name('sentservice')->middleware('auth.basic:users,phone');//->middleware('permission:view orders sent,users');

//user request service
    Route::post('servicerequest',[ServiceRequestController::class,'requestservice'])->name('servicerequest')->middleware('auth.basic:users,phone');


//accept and rejected services
    Route::post('acceptservice/{id}',[ServiceRequestController::class,'approve'])->middleware('auth.basic:users,phone');
    Route::post('rejectservice/{id}',[ServiceRequestController::class,'reject'])->middleware('auth.basic:users,phone');
//delete and stoped services and update status
    Route::delete('deleteservice/{id}',[ServiceController::class,'destroy'])->middleware('auth.basic:users,phone');
    Route::post('stoptmyservice/{id}',[ServiceController::class,'stopmyservice'])->middleware('auth.basic:users,phone');
    Route::put('editservice/{id}',[ServiceController::class,'editservice'])->middleware('auth.basic:users,phone');

    Route::get('/test-token', function () {
      $user = User::find(1); // غيّر الـ ID إذا بدك
      $token = $user->createToken('test-token')->plainTextToken;
    return response()->json([
        'token' => $token
    ]);
    });

//logout
    Route::post('/logout', function () {
      Auth::guard('users')->logout();
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.'
        ], 200);
    })
    ->middleware('auth:users')
    ->name('logout');



