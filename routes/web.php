<?php

use App\Http\Controllers\AdsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth_admin\AuthenticatedAdminSessionController;
use App\Http\Controllers\Auth_admin\RegisteredAdminController;
use App\Http\Controllers\Auth_superadmin\AuthenticatedSuperAdminSessionController;
use App\Http\Controllers\Auth_superadmin\RegisteredSuperAdminSessionController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\icons\RiIcons;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\BusinessDashboardController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// =================================================================
// LANGUAGE SWITCH (public — no auth needed)
// =================================================================

// Switch the application locale between supported languages
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'en|ar');

// =================================================================
// THEME / PANEL DEMO ROUTES  (no auth — UI components only)
// =================================================================

// Layout: without sidebar menu
Route::get('/layouts/without-menu',        [WithoutMenu::class, 'index'])->name('layouts-without-menu');
// Layout: without top navbar
Route::get('/layouts/without-navbar',      [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
// Layout: full-width fluid
Route::get('/layouts/fluid',               [Fluid::class, 'index'])->name('layouts-fluid');
// Layout: boxed container
Route::get('/layouts/container',           [Container::class, 'index'])->name('layouts-container');
// Layout: completely blank canvas
Route::get('/layouts/blank',               [Blank::class, 'index'])->name('layouts-blank');

// Page: account general settings
Route::get('/pages/account-settings-account',       [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
// Page: account notification preferences
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
// Page: account connected apps
Route::get('/pages/account-settings-connections',   [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
// Page: generic error view
Route::get('/pages/misc-error',            [MiscError::class, 'index'])->name('pages-misc-error');
// Page: under-maintenance splash screen
Route::get('/pages/misc-under-maintenance',[MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// UI component: basic card demo
Route::get('/cards/basic',                 [CardBasic::class, 'index'])->name('cards-basic');
// UI component: accordion demo
Route::get('/ui/accordion',                [Accordion::class, 'index'])->name('ui-accordion');
// UI component: alerts demo
Route::get('/ui/alerts',                   [Alerts::class, 'index'])->name('ui-alerts');
// UI component: badges demo
Route::get('/ui/badges',                   [Badges::class, 'index'])->name('ui-badges');
// UI component: buttons demo
Route::get('/ui/buttons',                  [Buttons::class, 'index'])->name('ui-buttons');
// UI component: carousel demo
Route::get('/ui/carousel',                 [Carousel::class, 'index'])->name('ui-carousel');
// UI component: collapse demo
Route::get('/ui/collapse',                 [Collapse::class, 'index'])->name('ui-collapse');
// UI component: dropdowns demo
Route::get('/ui/dropdowns',                [Dropdowns::class, 'index'])->name('ui-dropdowns');
// UI component: footer demo
Route::get('/ui/footer',                   [Footer::class, 'index'])->name('ui-footer');
// UI component: list groups demo
Route::get('/ui/list-groups',              [ListGroups::class, 'index'])->name('ui-list-groups');
// UI component: modals demo
Route::get('/ui/modals',                   [Modals::class, 'index'])->name('ui-modals');
// UI component: navbar demo
Route::get('/ui/navbar',                   [Navbar::class, 'index'])->name('ui-navbar');
// UI component: offcanvas demo
Route::get('/ui/offcanvas',                [Offcanvas::class, 'index'])->name('ui-offcanvas');
// UI component: pagination and breadcrumbs demo
Route::get('/ui/pagination-breadcrumbs',   [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
// UI component: progress bars demo
Route::get('/ui/progress',                 [Progress::class, 'index'])->name('ui-progress');
// UI component: spinners demo
Route::get('/ui/spinners',                 [Spinners::class, 'index'])->name('ui-spinners');
// UI component: tabs and pills demo
Route::get('/ui/tabs-pills',               [TabsPills::class, 'index'])->name('ui-tabs-pills');
// UI component: toasts demo
Route::get('/ui/toasts',                   [Toasts::class, 'index'])->name('ui-toasts');
// UI component: tooltips and popovers demo
Route::get('/ui/tooltips-popovers',        [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
// UI component: typography demo
Route::get('/ui/typography',               [Typography::class, 'index'])->name('ui-typography');
// Extended UI: perfect scrollbar demo
Route::get('/extended/ui-perfect-scrollbar',[PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
// Extended UI: text divider demo
Route::get('/extended/ui-text-divider',    [TextDivider::class, 'index'])->name('extended-ui-text-divider');
// Icons: Remix Icons set demo
Route::get('/icons/icons-ri',              [RiIcons::class, 'index'])->name('icons-ri');
// Forms: basic input elements demo
Route::get('/forms/basic-inputs',          [BasicInput::class, 'index'])->name('forms-basic-inputs');
// Forms: input groups demo
Route::get('/forms/input-groups',          [InputGroups::class, 'index'])->name('forms-input-groups');
// Forms: vertical layout demo
Route::get('/form/layouts-vertical',       [VerticalForm::class, 'index'])->name('form-layouts-vertical');
// Forms: horizontal layout demo
Route::get('/form/layouts-horizontal',     [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
// Tables: basic table demo
Route::get('/tables/basic',                [TablesBasic::class, 'index'])->name('tables-basic');

// =================================================================
// PUBLIC AUTH  (routes are defined in auth.php — required at bottom)
// =================================================================

// Log out the currently authenticated user (any guard) and redirect to login
Route::post('/logout', function () {
    if (Auth::guard('users')->check()) {
        Auth::guard('users')->user()->updateQuietly(['last_seen_at' => null]);
        Auth::guard('users')->logout();
    }
    Auth::guard('superadmins')->logout();
    return redirect()->route('login');
})->name('logout');

// =================================================================
// Superadmin-only routes
// =================================================================
Route::middleware('auth:superadmins')->group(function () {

    // Show the main analytics dashboard
    Route::get('/',              [Analytics::class, 'index'])->name('dashboard-analytics');
    // Superadmin home dashboard
    Route::get('dash',           [SuperadminController::class, 'index'])->name('dash');
    // Alias for superadmin dashboard index
    Route::get('indexsuperadmin',[SuperadminController::class, 'index'])->name('indexsuperadmin');


    // List all admins
    Route::get('adminsindex',       [SuperadminController::class, 'adminindex'])->name('adminsindex');
    // Full CRUD resource for admins
    Route::resource('admins',       AdminController::class);
    // Show create-admin form
    Route::get('createadmin',       [AdminController::class, 'create']);
    // Toggle active/inactive status for an admin
    Route::put('adminstatus/{id}',  [AdminController::class, 'status'])->name('adminstatus');
    // Show permission-edit form for an admin
    Route::get('edit/{id}',         [AdminController::class, 'editpermission'])->name('edit');
    // Save updated admin details
    Route::post('update/{id}',      [AdminController::class, 'updateadmin']);
    // Resource alias: admin index
    Route::resource('indexadmin',   AdminController::class);
    // Resource alias: admin role management
    Route::resource('adminrole',    AdminController::class);

    // List all roles
    Route::get('roleindex',  [RoleController::class, 'index'])->name('roleindex');
    // Create a new role
    Route::post('roleindex', [RoleController::class, 'store'])->name('roles.store');

    // Full CRUD resource for permissions
    Route::resource('permissions', PermissionController::class);

    // List all pending service requests for superadmin review
    Route::get('superadmin/service-requests',              [SuperadminController::class, 'serviceRequests'])->name('superadmin.service-requests');
    // Approve a specific service request
    Route::post('superadmin/service-requests/{id}/approve',[SuperadminController::class, 'approveServiceRequest'])->name('superadmin.service-requests.approve');
    // Reject a specific service request
    Route::post('superadmin/service-requests/{id}/reject', [SuperadminController::class, 'rejectServiceRequest'])->name('superadmin.service-requests.reject');

    // Mark all unread notifications as read
    Route::post('notifications/read-all', function () {
        auth('superadmins')->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');
    // Mark a single notification as read
    Route::post('notifications/{id}/read', function ($id) {
        auth('superadmins')->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    })->name('notifications.read');

    // Update the superadmin's own profile
    Route::post('superadmin/profile/update', [SuperadminController::class, 'updateProfile'])->name('superadmin.profile.update');

    // Reports management (superadmin has full access without permission middleware)
    Route::get('superadmin/reports',                  [ReportController::class, 'index'])->name('superadmin.reports.index');
    Route::post('superadmin/updatereport/{id}',        [ReportController::class, 'updateStatus'])->name('superadmin.report.updateStatus');
    Route::delete('superadmin/deletereport/{id}',      [ReportController::class, 'destroy'])->name('superadmin.report.destroy');
});

// =================================================================
// Shared admin + superadmin routes
// =================================================================
Route::middleware('auth:admins,superadmins')->group(function () {

    // Global dashboard search (accessible to both admins and superadmins)
    Route::get('dashboard/search',  [SearchController::class, 'search'])->name('dashboard.search');
    Route::get('dashboard/filters', [SearchController::class, 'filters'])->name('dashboard.filters');

    // List all registered users
    Route::resource('allindex', UserController::class);
    // Toggle a user's active/suspended status
    Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Show the admin panel dashboard
    Route::get('admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    // Update the admin's own profile
    Route::post('admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');
    // Mark all admin notifications as read
    Route::post('admin/notifications/read-all', [AdminController::class, 'markAllNotificationsRead'])->name('admin.notifications.read-all');

    // Approve a business account (requires accept business account permission)
    Route::post('approve/{id}', [BusinessController::class, 'approve'])->name('approve')
        ->middleware('permission:accept business account');
    // Reject a business account (requires reject business account permission)
    Route::post('reject/{id}',  [BusinessController::class, 'reject'])->name('reject')
        ->middleware('permission:reject business account');

    // Full CRUD resource for categories (requires category permissions)
    Route::resource('categories',   CategoryController::class)
        ->middleware('permission:add category|edit category|delete category');
    // Full CRUD resource for subcategories (requires subcategory permissions)
    Route::resource('subcategories', SubcategoryController::class)
        ->middleware('permission:add subcategory|edit subcategory|delete subcategory');

    // Full CRUD resource for cities (requires add city permission)
    Route::resource('cities', CityController::class)
        ->middleware('permission:add city');

    // Approve a service listing (requires accept service permission)
    Route::post('approveser/{id}',  [ServiceController::class, 'approve'])->name('approveser')
        ->middleware('permission:accept service');
    // Reject a service listing (requires reject service permission)
    Route::post('rejectser/{id}',   [ServiceController::class, 'rejected'])->name('rejectser')
        ->middleware('permission:reject service');
    // Mark a service listing as pending (requires pending service permission)
    Route::post('pendingser/{id}',  [ServiceController::class, 'pending'])->name('pendingser')
        ->middleware('permission:pending service');

    // Approve a received service request (requires accept service permission)
    Route::post('approveserrec/{id}', [ServiceRequestController::class, 'approverec'])->name('approveserrec')
        ->middleware('permission:accept service');
    // Reject a received service request (requires reject service permission)
    Route::post('rejectserrec/{id}',  [ServiceController::class, 'rejectedrec'])->name('rejectserrec')
        ->middleware('permission:reject service');
    // Mark a received service request as pending (requires pending service permission)
    Route::post('pendingserrec/{id}', [ServiceController::class, 'pendingrec'])->name('pendingserrec')
        ->middleware('permission:pending service');

    // Approve one of the admin's own services (requires accept service permission)
    Route::post('approvesermy/{id}', [ServiceController::class, 'approvemy'])->name('approvesermy')
        ->middleware('permission:accept service');
    // Reject one of the admin's own services (requires reject service permission)
    Route::post('rejectsermy/{id}',  [ServiceController::class, 'rejectedmy'])->name('rejectsermy')
        ->middleware('permission:reject service');
    // Mark one of the admin's own services as pending (requires pending service permission)
    Route::post('pendingsermy/{id}', [ServiceController::class, 'pendingmy'])->name('pendingsermy')
        ->middleware('permission:pending service');

    // List services owned by the authenticated admin
    Route::get('myservices',      [ServiceController::class, 'myservice'])->name('myservices');
    // List service requests received by the admin
    Route::get('servicereceived', [ServiceRequestController::class, 'received'])->name('servicereceived');
    // List service requests sent by the admin
    Route::get('sentservice',     [ServiceRequestController::class, 'sentservice'])->name('sentservice');
    // Store a new service
    Route::post('service',        [ServiceController::class, 'store'])->name('service.store');
    // Full CRUD resource for services
    Route::resource('services',   ServiceController::class);

    // List all submitted reports (requires manage report permission)
    Route::get('reports',              [ReportController::class, 'index'])->name('reports.index')
        ->middleware('permission:manage report');
    // Update the status of a report (requires manage report permission)
    Route::post('updatereport/{id}',   [ReportController::class, 'updateStatus'])->name('report.updateStatus')
        ->middleware('permission:manage report');
    // Delete a report (requires manage report permission)
    Route::delete('deletereport/{id}', [ReportController::class, 'destroy'])->name('report.destroy')
        ->middleware('permission:manage report');

    // Full CRUD resource for reviews
    Route::resource('reviews', ReviewController::class);

    // Full CRUD resource for businesses
    Route::resource('business', BusinessController::class);

    // List all ads
    Route::get('ads',                [AdsController::class, 'index'])->name('ads.index');
    // Show the create-ad form
    Route::get('ads/create',         [AdsController::class, 'create'])->name('ads.create');
    // Store a new ad
    Route::post('ads',               [AdsController::class, 'store'])->name('ads.store');
    // Show the edit form for an ad
    Route::get('ads/{id}/edit',      [AdsController::class, 'edit'])->name('ads.edit');
    // Update an existing ad
    Route::put('ads/{id}',           [AdsController::class, 'update'])->name('ads.update');
    // Delete an ad
    Route::delete('ads/{id}',        [AdsController::class, 'destroy'])->name('ads.destroy');
    // Toggle an ad's active/inactive state
    Route::post('ads/{id}/toggle',   [AdsController::class, 'toggle'])->name('ads.toggle');

    // List all services across the platform
    Route::get('allservices',  [ServiceController::class, 'allservices'])->name('allservices');
    // List all services in the admin view
    Route::get('allserviesad', [ServiceController::class, 'allser'])->name('allserviesad');
});

// =================================================================
// User-only routes
// =================================================================
Route::middleware('auth:users')->group(function () {

    // Show the user dashboard
    Route::get('dashi',   [UserController::class, 'dash'])->name('dashi');
    // Show the user's edit profile form
    Route::put('pu',      [UserController::class, 'edit']);
    // Save updated user profile
    Route::put('pud',     [UserController::class, 'update']);
    // Show the account profile settings page
    Route::get('profilee', [ProfileController::class, 'edit'])->name('profile.edit');
    // Save profile changes
    Route::post('profileu',[ProfileController::class, 'update'])->name('profile.update');

    // Browse all available services
    Route::get('allservices-user',          [ServiceRequestController::class, 'allservices'])->name('allservices.user');
    // Submit a request for a specific service
    Route::post('servicerequest/{id}',      [ServiceRequestController::class, 'requestservice'])->name('servicerequest.store');

    // List service requests sent by the user
    Route::get('sentservice-user',          [ServiceRequestController::class, 'sentservice'])->name('sentservice.user');

    // List incoming requests on the user's own services
    Route::get('incoming-user',             [ServiceRequestController::class, 'incoming'])->name('incoming.user');
    // Approve an incoming service request
    Route::post('servicerequest/{id}/approve', [ServiceRequestController::class, 'approve'])->name('servicerequest.approve');
    // Reject an incoming service request
    Route::post('servicerequest/{id}/reject',  [ServiceRequestController::class, 'reject'])->name('servicerequest.reject');

    // List approved service requests received by the user
    Route::get('servicereceived-user',      [ServiceRequestController::class, 'received'])->name('servicereceived.user');

    // List the user's own services
    Route::get('myservices-user',           [ServiceController::class, 'myservice'])->name('myservices.user');

    // Show the create-service form for a user
    Route::get('user-service/create',       [ServiceController::class, 'create'])->name('user.service.create');
    // Store a new service posted by the user
    Route::post('user-service',             [ServiceController::class, 'store'])->name('user.service.store');
    // Show the edit form for a user's service
    Route::get('user-service/{id}/edit',    [ServiceController::class, 'edit'])->name('user.service.edit');
    // Update a user's existing service
    Route::put('user-service/{id}',         [ServiceController::class, 'update'])->name('user.service.update');
    // Delete a user's service
    Route::delete('user-service/{id}',      [ServiceController::class, 'destroy'])->name('user.service.destroy');

    // Show the create-business form for a user
    Route::get('user-business/create',      [BusinessController::class, 'create'])->name('user.business.create');
    // Store a new business registered by the user
    Route::post('user-business',            [BusinessController::class, 'store'])->name('user.business.store');
    // Show the edit form for a user's business
    Route::get('user-business/{id}/edit',   [BusinessController::class, 'edit'])->name('user.business.edit');
    // Update a user's existing business
    Route::put('user-business/{id}',        [BusinessController::class, 'update'])->name('user.business.update');
    // Delete a user's business
    Route::delete('user-business/{id}',     [BusinessController::class, 'destroy'])->name('user.business.destroy');

    // Show the user's saved favorites
    Route::get('favorite',        [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorite');
    // Add or remove a service from favorites
    Route::post('favorite/{id}',  [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // Submit a report against a service
    Route::post('report/{id}', [ReportController::class, 'store'])->name('report.store');

    // Show the business owner's dashboard
    Route::get('business-dashboard', [BusinessDashboardController::class, 'index'])->name('business.dashboard');

    // Submit a review for a completed service request
    Route::post('review/{requestId}', [ReviewController::class, 'store'])->name('review.store');

    // Return online/last-seen status for a user (used by chat polling)
    Route::get('/user/{id}/status', function ($id) {
        $user = \App\Models\User::find($id);
        if (!$user) return response()->json(['is_online' => false, 'label' => 'Offline']);
        return response()->json([
            'is_online' => $user->isOnline(),
            'label'     => $user->lastSeenLabel(),
        ]);
    })->name('user.status');

    // Register or update the user's FCM device token for push notifications
    Route::post('/fcm/register-token', [FcmController::class, 'storeToken'])->name('fcm.register-token');

    // Show the private chat conversation list
    Route::get('/chat',           [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    // Show a private conversation with a specific user
    Route::get('/chat/{userId}',  [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    // Send a message to a specific user
    Route::post('/chat/{userId}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');

    // Browse active ads shown to users
    Route::get('ads-browse', [AdsController::class, 'browse'])->name('ads.browse');

    // Show the payment checkout page for a service request
    Route::get('/payment/checkout/{requestId}',            [PaymentController::class, 'checkout'])->name('payment.checkout');
    // Create a Stripe checkout session for a service request
    Route::post('/payment/stripe/{requestId}',             [PaymentController::class, 'createStripeSession'])->name('payment.stripe');
    // Handle Stripe payment success redirect
    Route::get('/payment/stripe/success/{requestId}',      [PaymentController::class, 'stripeSuccess'])->name('payment.stripe.success');
    // Create a PayPal order for a service request
    Route::post('/payment/paypal/{requestId}/create',      [PaymentController::class, 'createPaypalOrder'])->name('payment.paypal.create');
    // Capture and finalise a PayPal order
    Route::post('/payment/paypal/{requestId}/capture',     [PaymentController::class, 'capturePaypalOrder'])->name('payment.paypal.capture');
    // Process a bank transfer payment for a service request
    Route::post('/payment/bank-transfer/{requestId}',      [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    // Show the payment success confirmation page
    Route::get('/payment/success/{requestId}',             [PaymentController::class, 'success'])->name('payment.success');
    // Show the payment failure page
    Route::get('/payment/failed/{requestId}',              [PaymentController::class, 'failed'])->name('payment.failed');
    // Process a test payment for a service request
    Route::post('/payment/test/{requestId}',               [PaymentController::class, 'processTest'])->name('payment.test');
});

// =================================================================
// STRIPE WEBHOOK (no auth — Stripe sends directly)
// =================================================================

// Receive and handle Stripe webhook events (CSRF excluded)
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])
    ->name('webhooks.stripe')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// =================================================================
// Shared admin + superadmin routes (single routes outside group)
// =================================================================

// Register or update an admin's FCM device token for push notifications
Route::post('/fcm/admin-token', [FcmController::class, 'storeAdminToken'])
    ->name('fcm.admin.token')
    ->middleware('auth:admins,superadmins');

// =================================================================
require __DIR__.'/auth.php';
