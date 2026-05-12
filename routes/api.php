<?php
use App\Http\Controllers\Api\Auth\AuthenticatedUserController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// =========================
// Auth Routes
// =========================
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login',    [AuthenticatedUserController::class, 'store']);

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);
Route::post('/reset-password',  [NewPasswordController::class, 'store']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',          [AuthenticatedUserController::class, 'destroy']);
    Route::put('/password',         [PasswordController::class, 'update']);
});

Route::get('send', [FcmController::class, 'send']);

//notification routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);
});

// =========================
// All remaining API routes wrapped with 'api.' name prefix
// to avoid conflicts with web route names (business.index, reports.index, etc.)
// =========================
Route::group(['as' => 'api.'], function () {

    //report
    Route::post('report/{id}', [ReportController::class, 'store'])->name('report.store');
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('updatereport/{id}', [ReportController::class, 'updateStatus'])->name('report.updateStatus');
    Route::delete('deletereport/{id}', [ReportController::class, 'destroy'])->name('report.destroy');

    //favorite
    Route::post('/favorite/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

    //reviews
    Route::resource('reviews', ReviewController::class);

    //business
    Route::resource('business', BusinessController::class)->middleware('auth.basic:users,phone');

    //users
    Route::resource('allindex', UserController::class);
    Route::get('users', [ApiUserController::class, 'index'])->middleware('auth.basic:users,phone');
    Route::get('users/{id}', [ApiUserController::class, 'show'])->middleware('auth.basic:users,phone');
    Route::put('pu', [UserController::class, 'edit'])->middleware('auth:users');
    Route::put('pud', [UserController::class, 'update'])->middleware('auth:users');
    Route::get('profile', [ProfileController::class, 'index'])->middleware('auth.basic:users,phone');
    Route::put('updateprofile', [ProfileController::class, 'update'])->middleware('auth.basic:users,phone');
    //services
    Route::get('allservices', [ServiceController::class, 'allservices'])->name('allservices')->middleware('auth.basic:users,phone');
    Route::get('myservices', [ServiceController::class, 'myservice'])->name('myservices')->middleware('auth.basic:users,phone');
    Route::resource('services', ServiceController::class);
    Route::post('service', [ServiceController::class, 'store'])->name('service.store')->middleware('auth.basic:users,phone');

    //requests services
    Route::get('servicereceived', [ServiceRequestController::class, 'received'])->middleware('auth.basic:users,phone');

    //sent services
    Route::get('sentservice', [ServiceRequestController::class, 'sentservice'])->name('sentservice')->middleware('auth.basic:users,phone');

    //approved services (services user requested and got approved)
    Route::get('approvedservices', [ServiceRequestController::class, 'approvedservices'])->middleware('auth.basic:users,phone');

    //completed services (approved requests on services I own - both rent and sale)
    Route::get('completedservices', [ServiceRequestController::class, 'completedservices'])->middleware('auth.basic:users,phone');

    //user request service
    Route::post('servicerequest/{id}', [ServiceRequestController::class, 'requestservice'])->name('servicerequest')->middleware('auth.basic:users,phone');

    //accept and reject services
    Route::post('acceptservice/{id}', [ServiceRequestController::class, 'approve'])->middleware('auth.basic:users,phone');
    Route::post('rejectservice/{id}', [ServiceRequestController::class, 'reject'])->middleware('auth.basic:users,phone');

    //delete and stop services and update status
    Route::delete('deleteservice/{id}', [ServiceController::class, 'destroy'])->middleware('auth.basic:users,phone');
    Route::post('stoptmyservice/{id}', [ServiceController::class, 'stopmyservice'])->middleware('auth.basic:users,phone');
    Route::put('editservice/{id}', [ServiceController::class, 'editservice'])->middleware('auth.basic:users,phone');

    //logout for api users
    Route::post('/logout', function () {
        Auth::guard('users')->logout();
        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.'
        ], 200);
    })->middleware('auth:users')->name('logout');
});

Route::get('/test-token', function () {
    $user = User::find(2);
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    $token = $user->createToken('test-token')->plainTextToken;
    return response()->json([
        'token' => $token
    ]);
});
