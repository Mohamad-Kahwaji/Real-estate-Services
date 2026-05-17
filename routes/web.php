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
Route::get('/language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->where('locale', 'en|ar');

// =================================================================
// THEME / PANEL DEMO ROUTES  (no auth — UI components only)
// =================================================================
Route::get('/layouts/without-menu',        [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar',      [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid',               [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container',           [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank',               [Blank::class, 'index'])->name('layouts-blank');

Route::get('/pages/account-settings-account',       [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections',   [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error',            [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance',[MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

Route::get('/cards/basic',                 [CardBasic::class, 'index'])->name('cards-basic');
Route::get('/ui/accordion',                [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts',                   [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges',                   [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons',                  [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel',                 [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse',                 [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns',                [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer',                   [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups',              [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals',                   [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar',                   [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas',                [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs',   [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress',                 [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners',                 [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills',               [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts',                   [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers',        [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography',               [Typography::class, 'index'])->name('ui-typography');
Route::get('/extended/ui-perfect-scrollbar',[PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider',    [TextDivider::class, 'index'])->name('extended-ui-text-divider');
Route::get('/icons/icons-ri',              [RiIcons::class, 'index'])->name('icons-ri');
Route::get('/forms/basic-inputs',          [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups',          [InputGroups::class, 'index'])->name('forms-input-groups');
Route::get('/form/layouts-vertical',       [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal',     [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');
Route::get('/tables/basic',                [TablesBasic::class, 'index'])->name('tables-basic');

// =================================================================
// PUBLIC AUTH  (routes are defined in auth.php — required at bottom)
// =================================================================
Route::post('/logout', function () {
    if (Auth::guard('users')->check()) {
        Auth::guard('users')->user()->updateQuietly(['last_seen_at' => null]);
        Auth::guard('users')->logout();
    }
    Auth::guard('superadmins')->logout();
    return redirect()->route('login');
})->name('logout');

// =================================================================
// ██████  SUPERADMIN ONLY
// بس السوبر أدمن يلي بيوصلها
// =================================================================
Route::middleware('auth:superadmins')->group(function () {

    // Dashboard & Analytics
    Route::get('/',              [Analytics::class, 'index'])->name('dashboard-analytics');
    Route::get('dash',           [SuperadminController::class, 'index'])->name('dash');
    Route::get('indexsuperadmin',[SuperadminController::class, 'index'])->name('indexsuperadmin');

    // Search & Filters
    Route::get('dashboard/search',  [SearchController::class, 'search'])->name('dashboard.search');
    Route::get('dashboard/filters', [SearchController::class, 'filters'])->name('dashboard.filters');

    // Admin Management (إدارة الأدمنز)
    Route::get('adminsindex',       [SuperadminController::class, 'adminindex'])->name('adminsindex');
    Route::resource('admins',       AdminController::class);
    Route::get('createadmin',       [AdminController::class, 'create']);
    Route::put('adminstatus/{id}',  [AdminController::class, 'status'])->name('adminstatus');
    Route::get('edit/{id}',         [AdminController::class, 'editpermission'])->name('edit');
    Route::post('update/{id}',      [AdminController::class, 'updateadmin']);
    Route::resource('indexadmin',   AdminController::class);
    Route::resource('adminrole',    AdminController::class);

    // Role Management (إدارة الرولز)
    Route::get('roleindex',  [RoleController::class, 'index'])->name('roleindex');
    Route::post('roleindex', [RoleController::class, 'store'])->name('roles.store');

    // Permission Management (إدارة البيرميشن)
    Route::resource('permissions', PermissionController::class);

    // All Users Overview
    Route::resource('allindex', UserController::class);

    // All Services & Businesses Overview
    Route::get('allservices',  [ServiceController::class, 'allservices'])->name('allservices');
    Route::get('allserviesad', [ServiceController::class, 'allser'])->name('allserviesad');

    // Service Requests Management
    Route::get('superadmin/service-requests',              [SuperadminController::class, 'serviceRequests'])->name('superadmin.service-requests');
    Route::post('superadmin/service-requests/{id}/approve',[SuperadminController::class, 'approveServiceRequest'])->name('superadmin.service-requests.approve');
    Route::post('superadmin/service-requests/{id}/reject', [SuperadminController::class, 'rejectServiceRequest'])->name('superadmin.service-requests.reject');

    // Notifications
    Route::post('notifications/read-all', function () {
        auth('superadmins')->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.readAll');
    Route::post('notifications/{id}/read', function ($id) {
        auth('superadmins')->user()->notifications()->where('id', $id)->first()?->markAsRead();
        return back();
    })->name('notifications.read');

    // Superadmin Profile
    Route::post('superadmin/profile/update', [SuperadminController::class, 'updateProfile'])->name('superadmin.profile.update');
});

// =================================================================
// ██████  ADMIN — بالحسب الـ Role
// =================================================================
Route::middleware('auth:admins')->group(function () {

    // Admin Dashboard & Profile
    Route::get('admin-dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::post('admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // ── Business Manager ─────────────────────────────────────────
    // Role: business-manager
    Route::post('approve/{id}', [BusinessController::class, 'approve'])->name('approve')
        ->middleware('permission:accept business account');
    Route::post('reject/{id}',  [BusinessController::class, 'reject'])->name('reject')
        ->middleware('permission:reject business account');

    // ── Category Manager ──────────────────────────────────────────
    // Role: category-manager
    Route::resource('categories',   CategoryController::class)
        ->middleware('permission:add category|edit category|delete category');
    Route::resource('subcategories', SubcategoryController::class)
        ->middleware('permission:add subcategory|edit subcategory|delete subcategory');

    // ── City Manager ──────────────────────────────────────────────
    // Role: city-manager
    Route::resource('cities', CityController::class)
        ->middleware('permission:add city');

    // ── Service Manager ───────────────────────────────────────────
    // Role: service-manager
    Route::post('approveser/{id}',  [ServiceController::class, 'approve'])->name('approveser')
        ->middleware('permission:accept service');
    Route::post('rejectser/{id}',   [ServiceController::class, 'rejected'])->name('rejectser')
        ->middleware('permission:reject service');
    Route::post('pendingser/{id}',  [ServiceController::class, 'pending'])->name('pendingser')
        ->middleware('permission:pending service');

    Route::post('approveserrec/{id}', [ServiceRequestController::class, 'approverec'])->name('approveserrec')
        ->middleware('permission:accept service');
    Route::post('rejectserrec/{id}',  [ServiceController::class, 'rejectedrec'])->name('rejectserrec')
        ->middleware('permission:reject service');
    Route::post('pendingserrec/{id}', [ServiceController::class, 'pendingrec'])->name('pendingserrec')
        ->middleware('permission:pending service');

    Route::post('approvesermy/{id}', [ServiceController::class, 'approvemy'])->name('approvesermy')
        ->middleware('permission:accept service');
    Route::post('rejectsermy/{id}',  [ServiceController::class, 'rejectedmy'])->name('rejectsermy')
        ->middleware('permission:reject service');
    Route::post('pendingsermy/{id}', [ServiceController::class, 'pendingmy'])->name('pendingsermy')
        ->middleware('permission:pending service');

    // View services and requests (no extra permission — all admins)
    Route::get('myservices',      [ServiceController::class, 'myservice'])->name('myservices');
    Route::get('servicereceived', [ServiceRequestController::class, 'received'])->name('servicereceived');
    Route::get('sentservice',     [ServiceRequestController::class, 'sentservice'])->name('sentservice');
    Route::get('  ',         [ServiceController::class, 'create'])->name('service.create');
    Route::post('service',        [ServiceController::class, 'store'])->name('service.store');
    Route::resource('services',   ServiceController::class);

    // ── Report Manager ────────────────────────────────────────────
    // Role: report-manager
    Route::get('reports',              [ReportController::class, 'index'])->name('reports.index')
        ->middleware('permission:manage report');
    Route::post('updatereport/{id}',   [ReportController::class, 'updateStatus'])->name('report.updateStatus')
        ->middleware('permission:manage report');
    Route::delete('deletereport/{id}', [ReportController::class, 'destroy'])->name('report.destroy')
        ->middleware('permission:manage report');

    // Reviews (all admins can see)
    Route::resource('reviews', ReviewController::class);

    // Business overview (all admins can browse)
    Route::resource('business', BusinessController::class);

    // ── Ads Manager ───────────────────────────────────────────────
    Route::get('ads',                [AdsController::class, 'index'])->name('ads.index');
    Route::get('ads/create',         [AdsController::class, 'create'])->name('ads.create');
    Route::post('ads',               [AdsController::class, 'store'])->name('ads.store');
    Route::get('ads/{id}/edit',      [AdsController::class, 'edit'])->name('ads.edit');
    Route::put('ads/{id}',           [AdsController::class, 'update'])->name('ads.update');
    Route::delete('ads/{id}',        [AdsController::class, 'destroy'])->name('ads.destroy');
    Route::post('ads/{id}/toggle',   [AdsController::class, 'toggle'])->name('ads.toggle');
});

// =================================================================
// ██████  USER
// =================================================================
Route::middleware('auth:users')->group(function () {

    // Dashboard & Profile
    Route::get('dashi',   [UserController::class, 'dash'])->name('dashi');
    Route::put('pu',      [UserController::class, 'edit']);
    Route::put('pud',     [UserController::class, 'update']);
    Route::get('profilee', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('profileu',[ProfileController::class, 'update'])->name('profile.update');

    // Browse & Request Services
    Route::get('allservices-user',          [ServiceRequestController::class, 'allservices'])->name('allservices.user');
    Route::post('servicerequest/{id}',      [ServiceRequestController::class, 'requestservice'])->name('servicerequest.store');

    // Sent Requests (requests I made)
    Route::get('sentservice-user',          [ServiceRequestController::class, 'sentservice'])->name('sentservice.user');

    // Incoming Requests on my services (as business owner)
    Route::get('incoming-user',             [ServiceRequestController::class, 'incoming'])->name('incoming.user');
    Route::post('servicerequest/{id}/approve', [ServiceRequestController::class, 'approve'])->name('servicerequest.approve');
    Route::post('servicerequest/{id}/reject',  [ServiceRequestController::class, 'reject'])->name('servicerequest.reject');

    // Approved requests on my services
    Route::get('servicereceived-user',      [ServiceRequestController::class, 'received'])->name('servicereceived.user');

    // My own services
    Route::get('myservices-user',           [ServiceController::class, 'myservice'])->name('myservices.user');

    // Service CRUD for users (business owners)
    Route::get('user-service/create',       [ServiceController::class, 'create'])->name('user.service.create');
    Route::post('user-service',             [ServiceController::class, 'store'])->name('user.service.store');
    Route::get('user-service/{id}/edit',    [ServiceController::class, 'edit'])->name('user.service.edit');
    Route::put('user-service/{id}',         [ServiceController::class, 'update'])->name('user.service.update');
    Route::delete('user-service/{id}',      [ServiceController::class, 'destroy'])->name('user.service.destroy');

    // Business CRUD for users
    Route::get('user-business/create',      [BusinessController::class, 'create'])->name('user.business.create');
    Route::post('user-business',            [BusinessController::class, 'store'])->name('user.business.store');
    Route::get('user-business/{id}/edit',   [BusinessController::class, 'edit'])->name('user.business.edit');
    Route::put('user-business/{id}',        [BusinessController::class, 'update'])->name('user.business.update');
    Route::delete('user-business/{id}',     [BusinessController::class, 'destroy'])->name('user.business.destroy');

    // Favorites
    Route::get('favorite',        [\App\Http\Controllers\FavoriteController::class, 'index'])->name('favorite');
    Route::post('favorite/{id}',  [\App\Http\Controllers\FavoriteController::class, 'toggle'])->name('favorite.toggle');

    // Report a service
    Route::post('report/{id}', [ReportController::class, 'store'])->name('report.store');

    // Business Dashboard
    Route::get('business-dashboard', [BusinessDashboardController::class, 'index'])->name('business.dashboard');

    // Reviews
    Route::post('review/{requestId}', [ReviewController::class, 'store'])->name('review.store');

    // Online status (used by chat polling)
    Route::get('/user/{id}/status', function ($id) {
        $user = \App\Models\User::find($id);
        if (!$user) return response()->json(['is_online' => false, 'label' => 'Offline']);
        return response()->json([
            'is_online' => $user->isOnline(),
            'label'     => $user->lastSeenLabel(),
        ]);
    })->name('user.status');

    // FCM Token
    Route::post('/fcm/register-token', [FcmController::class, 'storeToken'])->name('fcm.register-token');

    // Private Chat
    Route::get('/chat',           [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{userId}',  [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{userId}', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');

    // Ads browse (read-only — active ads only)
    Route::get('ads-browse', [AdsController::class, 'browse'])->name('ads.browse');

    // Payment
    Route::get('/payment/checkout/{requestId}',            [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/stripe/{requestId}',             [PaymentController::class, 'createStripeSession'])->name('payment.stripe');
    Route::get('/payment/stripe/success/{requestId}',      [PaymentController::class, 'stripeSuccess'])->name('payment.stripe.success');
    Route::post('/payment/paypal/{requestId}/create',      [PaymentController::class, 'createPaypalOrder'])->name('payment.paypal.create');
    Route::post('/payment/paypal/{requestId}/capture',     [PaymentController::class, 'capturePaypalOrder'])->name('payment.paypal.capture');
    Route::post('/payment/bank-transfer/{requestId}',      [PaymentController::class, 'processBankTransfer'])->name('payment.bank-transfer');
    Route::get('/payment/success/{requestId}',             [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/failed/{requestId}',              [PaymentController::class, 'failed'])->name('payment.failed');
    Route::post('/payment/test/{requestId}',               [PaymentController::class, 'processTest'])->name('payment.test');
});

// =================================================================
// STRIPE WEBHOOK (no auth — Stripe sends directly)
// =================================================================
Route::post('/webhooks/stripe', [PaymentController::class, 'stripeWebhook'])
    ->name('webhooks.stripe')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// =================================================================
// SHARED — Admin + Superadmin
// =================================================================
Route::post('/fcm/admin-token', [FcmController::class, 'storeAdminToken'])
    ->name('fcm.admin.token')
    ->middleware('auth:admins,superadmins');

// =================================================================
require __DIR__.'/auth.php';
