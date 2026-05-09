<?php

use App\Http\Controllers\AdminController;
//use App\Http\Controllers\Auth\AuthenticatedSessionController;
//use App\Http\Controllers\Auth\ConfirmablePasswordController;
//use App\Http\Controllers\Auth\EmailVerificationNotificationController;
//use App\Http\Controllers\Auth\EmailVerificationPromptController;
//use App\Http\Controllers\Auth\NewPasswordController;
//use App\Http\Controllers\Auth\PasswordController;
//use App\Http\Controllers\Auth\PasswordResetLinkController;
//use App\Http\Controllers\Auth\VerifyEmailController;
//use App\Http\Controllers\Auth_superadmin\AuthenticatedSuperAdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SuperadminController;
use App\Http\Controllers\UserController;
use App\Models\Superadmin;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth_admin\AuthenticatedAdminSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth_admin\RegisteredAdminController;
use App\Http\Controllers\Auth_superadmin\RegisteredSuperAdminSessionController;
use App\Http\Controllers\Auth_superadmin\AuthenticatedSuperAdminSessionController;







Route::middleware('guest')->group(function () {
 /*   //Route::get('register', [RegisteredUserController::class, 'create'])
       // ->name('register');
    //Route::post('registerstore',[RegisteredUserController::class,'store']);


  //  Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('loginc',[AuthenticatedSuperAdminController::class,'create'])->name('login');






  Route::get('login-admin', [AdminController::class,'showLoginForm'])
        ->name('login-admin');

    Route::post('login-admin', [AdminController::class, 'login'])
        ->name('login-admin.store');

    Route::get('login-superadmin', [SuperadminController::class,'showLoginForm'])
        ->name('login-superadmin');

    Route::post('login-superadmin', [SuperadminController::class, 'login'])
        ->name('login-superadmin.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');*/
        // =========================
// User Auth
// =========================
Route::get('login', [AuthenticatedUserController::class, 'create'])->name('login');
Route::post('login', [AuthenticatedUserController::class, 'store'])->name('login.store');

Route::get('register', [RegisteredUserController::class, 'create'])->name('register.create');
Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

// =========================
// Admin Auth
// =========================
Route::get('logina', [AuthenticatedAdminSessionController::class, 'create'])->name('logina.create');
Route::post('logina', [AuthenticatedAdminSessionController::class, 'store'])->name('logina.store');

Route::get('registera', [RegisteredAdminController::class, 'create'])->name('registera.create');
Route::post('registera', [RegisteredAdminController::class, 'store'])->name('registera.store');

// =========================
// Superadmin Auth
// =========================
Route::get('loginsa', [AuthenticatedSuperAdminSessionController::class, 'create'])->name('loginsa.create');
Route::post('loginsa', [AuthenticatedSuperAdminSessionController::class, 'store'])->name('loginsa.store');

Route::get('registersa', [RegisteredSuperAdminSessionController::class, 'create'])->name('registersa.create');
Route::post('registersa', [RegisteredSuperAdminSessionController::class, 'store'])->name('registersa.store');

});

Route::middleware('auth')->group(function () {
//    Route::get('verify-email', EmailVerificationPromptController::class)
//        ->name('verification.notice');

  /*  Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');



    Route::get('registerc',[RegisteredUserController::class,'create'])->name('register.create');////
    Route::post('registers',[RegisteredUserController::class,'store'])->name('register.store');////
    Route::get('loginc',[AuthenticatedSessionController::class, 'create'])->name('login.create');////
    Route::post('logins',[AuthenticatedSessionController::class, 'store'])->name('login.store');/////*/


    //Route::get('rsuperadminc',[RegisteredAdminController::class,'create'])->name('registeradmin.create');////
    //Route::post('rsuperadmins',[RegisteredAdminController::class,'store'])->name('registeradmin.store');////


// =========================
// Superadmin Auth
// =========================
// User Auth
// =========================




});
