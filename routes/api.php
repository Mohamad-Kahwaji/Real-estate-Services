<?php
use App\Http\Controllers\Api\Auth\AuthenticatedUserController;
use App\Http\Controllers\Api\Auth\OtpController as ApiOtpController;
use App\Http\Controllers\Api\Auth\NewPasswordController;
use App\Http\Controllers\Api\Auth\PasswordController;
use App\Http\Controllers\Api\Auth\PasswordResetLinkController;
use App\Http\Controllers\Api\Auth\RegisteredUserController;
use App\Http\Controllers\Api\BusinessController;
use App\Http\Controllers\Api\ChatController as ApiChatController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\PaymentController as ApiPaymentController;
use App\Http\Controllers\Api\ServiceRequestController;
use App\Http\Controllers\Api\UserController as ApiUserController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =========================
// Public Auth Routes (no token required)
// =========================
Route::post('/register',       [RegisteredUserController::class,    'store']);
Route::post('/login',          [AuthenticatedUserController::class, 'store']);
Route::post('/verify-otp',     [ApiOtpController::class,           'verify']);
Route::post('/resend-otp',     [ApiOtpController::class,           'resend']);
Route::post('/forgot-password',[PasswordResetLinkController::class,'store']);
Route::post('/reset-password', [NewPasswordController::class,      'store']);

Route::get('send', [FcmController::class, 'send']);

// =========================
// Protected Routes (Bearer token required)
// =========================
Route::middleware('auth:sanctum')->group(function () {

    // ── Auth ─────────────────────────────────────────────────────────────────
    Route::post('/logout',   [AuthenticatedUserController::class, 'destroy']);
    Route::put('/password',  [PasswordController::class,          'update']);

    // ── Notifications ─────────────────────────────────────────────────────────
    Route::get('/notifications',       [NotificationController::class, 'index']);
    Route::get('/notifications/unread',[NotificationController::class, 'unread']);
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);

    // ── Profile ───────────────────────────────────────────────────────────────
    Route::get('profile',        [ProfileController::class, 'index']);
    Route::put('updateprofile',  [ProfileController::class, 'update']);
    Route::put('pu',             [UserController::class,    'edit']);
    Route::put('pud',            [UserController::class,    'update']);

    // ── Users ─────────────────────────────────────────────────────────────────
    Route::get('users',      [ApiUserController::class, 'index']);
    Route::get('users/{id}', [ApiUserController::class, 'show']);
    Route::resource('allindex', UserController::class);

    // ── Business ──────────────────────────────────────────────────────────────
    Route::resource('business', BusinessController::class);

    // ── Services ──────────────────────────────────────────────────────────────
    Route::get('allservices',         [ServiceController::class, 'allservices'])->name('api.allservices');
    Route::get('myservices',          [ServiceController::class, 'myservice'])->name('api.myservices');
    Route::post('service',            [ServiceController::class, 'store'])->name('api.service.store');
    Route::delete('deleteservice/{id}',[ServiceController::class, 'destroy']);
    Route::post('stoptmyservice/{id}',[ServiceController::class, 'stopmyservice']);
    Route::put('editservice/{id}',    [ServiceController::class, 'editservice']);
    Route::resource('services',       ServiceController::class);

    // ── Service Requests ──────────────────────────────────────────────────────
    Route::get('servicereceived',        [ServiceRequestController::class, 'received']);
    Route::get('sentservice',            [ServiceRequestController::class, 'sentservice'])->name('api.sentservice');
    Route::get('approvedservices',       [ServiceRequestController::class, 'approvedservices']);
    Route::get('completedservices',      [ServiceRequestController::class, 'completedservices']);
    Route::post('servicerequest/{id}',   [ServiceRequestController::class, 'requestservice'])->name('api.servicerequest');
    Route::post('acceptservice/{id}',    [ServiceRequestController::class, 'approve']);
    Route::post('rejectservice/{id}',    [ServiceRequestController::class, 'reject']);

    // ── Reports ───────────────────────────────────────────────────────────────
    Route::post('report/{id}',       [ReportController::class, 'store'])->name('api.report.store');
    Route::get('reports',            [ReportController::class, 'index'])->name('api.reports.index');
    Route::post('updatereport/{id}', [ReportController::class, 'updateStatus'])->name('api.report.updateStatus');
    Route::delete('deletereport/{id}',[ReportController::class, 'destroy'])->name('api.report.destroy');

    // ── Favorites ─────────────────────────────────────────────────────────────
    Route::post('/favorite/{id}', [FavoriteController::class, 'toggle'])->name('api.favorite.toggle');

    // ── Reviews ───────────────────────────────────────────────────────────────
    Route::resource('reviews', ReviewController::class)->except(['index']);
    Route::get('reviews/service/{serviceId}', [ReviewController::class, 'byService'])->name('api.reviews.by-service');

    // ── Chat ──────────────────────────────────────────────────────────────────
    Route::prefix('chat')->group(function () {
        Route::get('conversations',  [ApiChatController::class, 'conversations']);
        Route::get('unread',         [ApiChatController::class, 'unreadCount']);
        Route::get('{userId}',       [ApiChatController::class, 'show']);
        Route::post('{userId}',      [ApiChatController::class, 'store']);
        Route::delete('{messageId}', [ApiChatController::class, 'destroy']);
    });

    // ── Payment ───────────────────────────────────────────────────────────────
    Route::prefix('payment')->group(function () {
        Route::get('{requestId}',                 [ApiPaymentController::class, 'show']);
        Route::post('{requestId}/stripe/intent',  [ApiPaymentController::class, 'createStripeIntent']);
        Route::post('{requestId}/stripe/confirm', [ApiPaymentController::class, 'confirmStripe']);
        Route::post('{requestId}/paypal/create',  [ApiPaymentController::class, 'createPaypalOrder']);
        Route::post('{requestId}/paypal/capture', [ApiPaymentController::class, 'capturePaypalOrder']);
        Route::post('{requestId}/bank-transfer',  [ApiPaymentController::class, 'bankTransfer']);
        Route::post('{requestId}/test',           [ApiPaymentController::class, 'testPay']);
    });
});
