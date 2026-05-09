<?php

use App\Events\MessageSent;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth_admin\AuthenticatedAdminSessionController;
use App\Http\Controllers\Auth_admin\RegisteredAdminController;
use App\Http\Controllers\Auth_superadmin\AuthenticatedSuperAdminSessionController;
use App\Http\Controllers\Auth_superadmin\RegisteredSuperAdminSessionController;
use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\FavoriteController;
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
use App\Http\Controllers\UserController;
use App\Services\AdminPushNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
//Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
//Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
//Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/icons-ri', [RiIcons::class, 'index'])->name('icons-ri');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');







//accept & reject Business Account
    Route::post('approve/{id}',[BusinessController::class,'approve'])->name('approve');//->middleware('permission:accept business account');
    Route::post('reject/{id}',[BusinessController::class,'reject'])->name('reject');//->middleware('permission:reject business account');
//CRUD category
    Route::resource('categories',CategoryController::class);//->middleware(['auth:admins','permission:add category,edit category,delete category']);

//CRUD SubCategory
    Route::resource('subcategories',SubcategoryController::class);//->middleware(['auth:admins','permission:add subcategory,edit subcategory,delete subcategory']);
//CRUD cities
    Route::resource('cities',CityController::class);//->middleware(['auth:admins','permission:add city']);



    //Route::resource('allindex', UserController::class);
    Route::resource('business',BusinessController::class);
    Route::resource('allindex',UserController::class);

    //Route::resource('register',RegisteredUserController::class);
    //Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    //Route::post('register', [RegisteredUserController::class, 'store'])->name('register');

    Route::get('dashi',[UserController::class,'dash'])->name('dashi')->middleware('auth:users');
    Route::put('pu',[UserController::class,'edit'])->middleware('auth:users');
    Route::put('pud',[UserController::class,'update'])->middleware('auth:users');

    Route::get('profilee',[ProfileController::class,'edit'])->name('profile.edit')->middleware('auth:users');
    Route::post('profileu',[ProfileController::class,'update'])->name('profile.update')->middleware('auth:users');




    Route::resource('indexadmin',AdminController::class);
    Route::get('edit/{id}',[AdminController::class,'editpermission'])->name('edit');
    Route::post('update/{id}',[AdminController::class,'updateadmin']);
    //Route::resource('indexsuperadmin',SuperadminController::class);
    Route::resource('admins',AdminController::class);
    Route::get('createadmin',[AdminController::class,'create'])->middleware('permission:add admin');
    Route::resource('adminrole', AdminController::class);
    Route::put('adminstatus/{id}',[AdminController::class,'status'])->name('adminstatus');


    Route::get('roleindex',[RoleController::class,'index'])->name('roleindex');
    Route::resource('permissions',PermissionController::class);//->middleware('permission:[add role,
           // edit role,
           // delete role,
          //  set role]');

    Route::get('myservices',[ServiceController::class,'myservice'])->name('myservices');//->middleware('permission:view services,users');
    Route::resource('services',ServiceController::class);
    Route::get('servicereceived',[ServiceRequestController::class,'received'])->name('servicereceived');//->middleware('permission:view orders received,users');
    Route::get('sentservice',[ServiceRequestController::class,'sent'])->name('sentservice');//->middleware('permission:view orders sent,users');
    Route::get('service',[ServiceController::class,'create'])->name('service.create');
    Route::get('allservices',[ServiceController::class,'allservices'])->name('allservices');
    Route::post('service',[ServiceController::class,'store'])->name('service.store');

    Route::post('approveser/{id}',[ServiceController::class,'approve'])->name('approveser');//->middleware('permission:accept service');
    Route::post('rejectser/{id}',[ServiceController::class,'rejected'])->name('rejectser');//->middleware('permission:reject service');
    Route::post('pendingser/{id}',[ServiceController::class,'pending'])->name('pendingser');//->middleware('permission:pending service');

    Route::post('approveserrec/{id}',[ServiceRequestController::class,'approverec'])->name('approveserrec');//->middleware('permission:accept service');
    Route::post('rejectserrec/{id}',[ServiceController::class,'rejectedrec'])->name('rejectserrec');//->middleware('permission:reject service');
    Route::post('pendingserrec/{id}',[ServiceController::class,'pendingrec'])->name('pendingserrec');//->middleware('permission:pending service');

    Route::post('approvesermy/{id}',[ServiceController::class,'approvemy'])->name('approvesermy');//->middleware('permission:accept service');
    Route::post('rejectsermy/{id}',[ServiceController::class,'rejectedmy'])->name('rejectsermy');//->middleware('permission:reject service');
    Route::post('pendingsermy/{id}',[ServiceController::class,'pendingmy'])->name('pendingsermy');//->middleware('permission:pending service');

//    Route::get('servicerequest',[ServiceRequestController::class,'request'])->name('servicerequest');;

        Route::get('indexsuperadmin',[SuperadminController::class,'index'])->name('indexsuperadmin')->middleware('auth:superadmins');
        Route::get('adminsindex',[SuperadminController::class,'adminindex'])->name('adminsindex');
        Route::get('admin-dashboard',[AdminController::class,'index'])->name('admin.dashboard')->middleware('auth:admins');


        Route::get('allserviesad',[ServiceController::class,'allser'])->name('allserviesad');//->middleware('auth:users');
        Route::resource('reviews', ReviewController::class);

        Route::post('/fcm/register-token',[FcmController::class,'storeToken'])
        ->name('fcm.register-token')->middleware('auth:users');

//      Route::post('/favorite/{id}', [FavoriteController::class, 'toggle'])->name('favorite.toggle');

      Route::post('report/{id}',[ReportController::class,'store'])->name('report.store');
      Route::get('reports',[ReportController::class,'index'])->name('reports.index');
      Route::post('updatereport/{id}',[ReportController::class,'updateStatus'])->name('report.updateStatus');
      Route::delete('deletereport/{id}',[ReportController::class,'destroy'])->name('report.destroy');



        Route::post('/logout', function () {
        Auth::guard('users')->logout();
        Auth::guard('superadmins')->logout();
        return redirect()->route('login'
        );
        })->name('logout');




Route::get('login', [AuthenticatedUserController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedUserController::class, 'store'])->name('login.store');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register.create');
Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');



  // Private Chat
  Route::middleware('auth:users')->group(function () {
      Route::get('/chat',              [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
      Route::get('/chat/{userId}',     [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
      Route::post('/chat/{userId}',    [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.store');
  });


    Route::post('/fcm/admin-token', [FcmController::class, 'storeAdminToken'])
    ->name('fcm.admin.token')->middleware('auth:admins,superadmins');

    Route::get('dash',[SuperadminController::class,'index'])->name('dash')->middleware('auth:superadmins');

    

    require __DIR__.'/auth.php';

