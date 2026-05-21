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
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// =================================================================
// Public auth routes (no token required)
// =================================================================

// Register a new user account
Route::post('/register',       [RegisteredUserController::class,    'store']);
// Authenticate a user and return a Bearer token
Route::post('/login',          [AuthenticatedUserController::class, 'store']);
// Verify the OTP sent during registration or password reset
Route::post('/verify-otp',     [ApiOtpController::class,           'verify']);
// Resend the OTP to the user's contact
Route::post('/resend-otp',     [ApiOtpController::class,           'resend']);
// Send a password-reset link to the user's email
Route::post('/forgot-password',[PasswordResetLinkController::class,'store']);
// Set a new password using the reset token
Route::post('/reset-password', [NewPasswordController::class,      'store']);

// Send a test FCM push notification
Route::get('send', [FcmController::class, 'send']);

// =================================================================
// Protected routes (Bearer token required)
// =================================================================
Route::middleware('auth:sanctum')->group(function () {

    // ── Auth ─────────────────────────────────────────────────────────────────

    // Revoke the current user's token (logout)
    Route::post('/logout',   [AuthenticatedUserController::class, 'destroy']);
    // Update the authenticated user's password
    Route::put('/password',  [PasswordController::class,          'update']);

    // ── Notifications ─────────────────────────────────────────────────────────

    // List all notifications for the authenticated user
    Route::get('/notifications',       [NotificationController::class, 'index']);
    // List only unread notifications
    Route::get('/notifications/unread',[NotificationController::class, 'unread']);
    // Mark specified notifications as read
    Route::post('/notifications/read', [NotificationController::class, 'markAsRead']);

    // ── Profile ───────────────────────────────────────────────────────────────

    // Return the authenticated user's profile data
    Route::get('profile',        [ProfileController::class, 'index']);
    // Update the authenticated user's profile
    Route::put('updateprofile',  [ProfileController::class, 'update']);
    // Show the user's edit profile form
    Route::put('pu',             [UserController::class,    'edit']);
    // Save updated user profile details
    Route::put('pud',            [UserController::class,    'update']);

    // ── Users ─────────────────────────────────────────────────────────────────

    // List all users
    Route::get('users',      [ApiUserController::class, 'index']);
    // Return a single user by ID
    Route::get('users/{id}', [ApiUserController::class, 'show']);
    // Full CRUD resource for user management (namespaced to avoid conflicts)
    Route::resource('allindex', UserController::class)->names([
        'index'   => 'api.allindex.index',
        'create'  => 'api.allindex.create',
        'store'   => 'api.allindex.store',
        'show'    => 'api.allindex.show',
        'edit'    => 'api.allindex.edit',
        'update'  => 'api.allindex.update',
        'destroy' => 'api.allindex.destroy',
    ]);

    // ── Business ──────────────────────────────────────────────────────────────

    // Full CRUD resource for businesses
    Route::resource('business', BusinessController::class);

    // ── Services ──────────────────────────────────────────────────────────────

    // List all available services on the platform
    Route::get('allservices',         [ServiceController::class, 'allservices'])->name('api.allservices');
    // List services owned by the authenticated user
    Route::get('myservices',          [ServiceController::class, 'myservice'])->name('api.myservices');
    // Store a new service
    Route::post('service',            [ServiceController::class, 'store'])->name('api.service.store');
    // Delete a specific service by ID
    Route::delete('deleteservice/{id}',[ServiceController::class, 'destroy']);
    // Stop (deactivate) one of the user's own services
    Route::post('stoptmyservice/{id}',[ServiceController::class, 'stopmyservice']);
    // Update (edit) a specific service
    Route::put('editservice/{id}',    [ServiceController::class, 'editservice']);
    // Full CRUD resource for services
    Route::resource('services',       ServiceController::class);

    // ── Service Requests ──────────────────────────────────────────────────────

    // List service requests received by the authenticated user
    Route::get('servicereceived',        [ServiceRequestController::class, 'received']);
    // List service requests sent by the authenticated user
    Route::get('sentservice',            [ServiceRequestController::class, 'sentservice'])->name('api.sentservice');
    // List approved service requests for the user
    Route::get('approvedservices',       [ServiceRequestController::class, 'approvedservices']);
    // List completed service requests for the user
    Route::get('completedservices',      [ServiceRequestController::class, 'completedservices']);
    // Submit a new request for a specific service
    Route::post('servicerequest/{id}',   [ServiceRequestController::class, 'requestservice'])->name('api.servicerequest');
    // Approve an incoming service request
    Route::post('acceptservice/{id}',    [ServiceRequestController::class, 'approve']);
    // Reject an incoming service request
    Route::post('rejectservice/{id}',    [ServiceRequestController::class, 'reject']);

    // ── Reports ───────────────────────────────────────────────────────────────

    // Submit a report against a service
    Route::post('report/{id}',       [ReportController::class, 'store'])->name('api.report.store');
    // List all reports (admin use)
    Route::get('reports',            [ReportController::class, 'index'])->name('api.reports.index');
    // Update the status of a report
    Route::post('updatereport/{id}', [ReportController::class, 'updateStatus'])->name('api.report.updateStatus');
    // Delete a report by ID
    Route::delete('deletereport/{id}',[ReportController::class, 'destroy'])->name('api.report.destroy');

    // ── Favorites ─────────────────────────────────────────────────────────────

    // Add or remove a service from the user's favorites
    Route::post('/favorite/{id}', [FavoriteController::class, 'toggle'])->name('api.favorite.toggle');

    // ── Reviews ───────────────────────────────────────────────────────────────

    // Full CRUD resource for reviews (index excluded — use the by-service route)
    Route::resource('reviews', ReviewController::class)->except(['index']);
    // List all reviews for a specific service
    Route::get('reviews/service/{serviceId}', [ReviewController::class, 'byService'])->name('api.reviews.by-service');

    // ── Chat ──────────────────────────────────────────────────────────────────
    Route::prefix('chat')->group(function () {
        // List all chat conversations for the authenticated user
        Route::get('conversations',  [ApiChatController::class, 'conversations']);
        // Return the total unread message count
        Route::get('unread',         [ApiChatController::class, 'unreadCount']);
        // Show the conversation with a specific user
        Route::get('{userId}',       [ApiChatController::class, 'show']);
        // Send a message to a specific user
        Route::post('{userId}',      [ApiChatController::class, 'store']);
        // Delete a specific chat message
        Route::delete('{messageId}', [ApiChatController::class, 'destroy']);
    });

    // ── Payment ───────────────────────────────────────────────────────────────
    Route::prefix('payment')->group(function () {
        // Return payment details for a service request
        Route::get('{requestId}',                 [ApiPaymentController::class, 'show']);
        // Create a Stripe PaymentIntent for a service request
        Route::post('{requestId}/stripe/intent',  [ApiPaymentController::class, 'createStripeIntent']);
        // Confirm a Stripe payment for a service request
        Route::post('{requestId}/stripe/confirm', [ApiPaymentController::class, 'confirmStripe']);
        // Create a PayPal order for a service request
        Route::post('{requestId}/paypal/create',  [ApiPaymentController::class, 'createPaypalOrder']);
        // Capture and finalise a PayPal order
        Route::post('{requestId}/paypal/capture', [ApiPaymentController::class, 'capturePaypalOrder']);
        // Process a bank transfer payment for a service request
        Route::post('{requestId}/bank-transfer',  [ApiPaymentController::class, 'bankTransfer']);
        // Process a test payment for a service request
        Route::post('{requestId}/test',           [ApiPaymentController::class, 'testPay']);
    });
});
