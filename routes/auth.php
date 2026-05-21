<?php

use App\Http\Controllers\Auth\AuthenticatedUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth_admin\AuthenticatedAdminSessionController;
use App\Http\Controllers\Auth_admin\ForgotPasswordController as AdminForgotPasswordController;
use App\Http\Controllers\Auth_admin\RegisteredAdminController;
use App\Http\Controllers\Auth_superadmin\AuthenticatedSuperAdminSessionController;
use App\Http\Controllers\Auth_superadmin\ForgotPasswordController as SuperadminForgotPasswordController;
use App\Http\Controllers\Auth_superadmin\RegisteredSuperAdminSessionController;
use Illuminate\Support\Facades\Route;

// =========================
// Guest-only Auth Routes
// (redirect to home if already logged in as a user)
// =========================
Route::middleware('guest')->group(function () {

    // User Auth
    Route::get('login',    [AuthenticatedUserController::class, 'create'])->name('login');
    Route::post('login',   [AuthenticatedUserController::class, 'store'])->name('login.store');
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register.create');
    Route::post('register',[RegisteredUserController::class, 'store'])->name('register.store');

    // Forgot Password
    Route::get('forgot-password',  [ForgotPasswordController::class, 'showForm'])->name('forgot-password');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('forgot-password.send');
    Route::get('reset-password',   [ForgotPasswordController::class, 'showVerify'])->name('reset-password.verify');
    Route::post('reset-password',  [ForgotPasswordController::class, 'savePassword'])->name('reset-password.save');

    // Admin Auth
    Route::get('logina',    [AuthenticatedAdminSessionController::class, 'create'])->name('logina.create');
    Route::post('logina',   [AuthenticatedAdminSessionController::class, 'store'])->name('logina.store');
    Route::get('registera', [RegisteredAdminController::class, 'create'])->name('registera.create');
    Route::post('registera',[RegisteredAdminController::class, 'store'])->name('registera.store');

    // Admin Forgot Password
    Route::get('admin/forgot-password',  [AdminForgotPasswordController::class, 'showForm'])->name('admin.forgot-password');
    Route::post('admin/forgot-password', [AdminForgotPasswordController::class, 'sendOtp'])->name('admin.forgot-password.send');
    Route::get('admin/reset-password',   [AdminForgotPasswordController::class, 'showVerify'])->name('admin.reset-password.verify');
    Route::post('admin/reset-password',  [AdminForgotPasswordController::class, 'savePassword'])->name('admin.reset-password.save');

    // Superadmin Auth
    Route::get('loginsa',    [AuthenticatedSuperAdminSessionController::class, 'create'])->name('loginsa.create');
    Route::post('loginsa',   [AuthenticatedSuperAdminSessionController::class, 'store'])->name('loginsa.store');
    Route::get('registersa', [RegisteredSuperAdminSessionController::class, 'create'])->name('registersa.create');
    Route::post('registersa',[RegisteredSuperAdminSessionController::class, 'store'])->name('registersa.store');

    // Superadmin Forgot Password
    Route::get('superadmin/forgot-password',  [SuperadminForgotPasswordController::class, 'showForm'])->name('superadmin.forgot-password');
    Route::post('superadmin/forgot-password', [SuperadminForgotPasswordController::class, 'sendOtp'])->name('superadmin.forgot-password.send');
    Route::get('superadmin/reset-password',   [SuperadminForgotPasswordController::class, 'showVerify'])->name('superadmin.reset-password.verify');
    Route::post('superadmin/reset-password',  [SuperadminForgotPasswordController::class, 'savePassword'])->name('superadmin.reset-password.save');
});

// =========================
// OTP Verification (mid-auth, no guard needed)
// =========================
Route::get('/otp',         [OtpController::class, 'show'])->name('otp.show');
Route::post('/otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/otp/resend', [OtpController::class, 'resend'])->name('otp.resend');
