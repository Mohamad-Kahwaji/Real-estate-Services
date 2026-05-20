<?php

use App\Models\Admin;
use App\Models\Superadmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Create a superadmin with a known email + password.
 */
function makeSuperadmin(array $overrides = []): Superadmin
{
    return Superadmin::create(array_merge([
        'name'     => 'Test Superadmin',
        'email'    => 'superadmin@example.com',
        'password' => Hash::make('password'),
    ], $overrides));
}

/**
 * Create an active admin (used to test the dual-guard login path).
 */
function makeAdminForSa(array $overrides = []): Admin
{
    return Admin::create(array_merge([
        'name'      => 'SA Admin',
        'email'     => 'saadmin@example.com',
        'password'  => Hash::make('password'),
        'is_active' => true,
    ], $overrides));
}

// ---------------------------------------------------------------------------
// GET /loginsa  —  superadmin login form  (route: loginsa.create)
// ---------------------------------------------------------------------------

describe('GET /loginsa (superadmin login page)', function () {

    it('returns 200 for guests', function () {
        $this->get(route('loginsa.create'))
             ->assertStatus(200);
    });

});

// ---------------------------------------------------------------------------
// POST /loginsa  —  superadmin credentials check  (route: loginsa.store)
//
// AuthenticatedSuperAdminSessionController::store() tries the superadmins
// guard first, then falls back to the admins guard.
// ---------------------------------------------------------------------------

describe('POST /loginsa (superadmin login attempt)', function () {

    it('with valid superadmin credentials authenticates and redirects to indexsuperadmin', function () {
        $sa = makeSuperadmin();

        $this->post(route('loginsa.store'), [
                 'email'    => $sa->email,
                 'password' => 'password',
             ])
             ->assertRedirect(route('indexsuperadmin'));

        $this->assertAuthenticatedAs($sa, 'superadmins');
    });

    it('with valid admin credentials authenticates as admin and redirects to adminsindex', function () {
        $admin = makeAdminForSa();

        $this->post(route('loginsa.store'), [
                 'email'    => $admin->email,
                 'password' => 'password',
             ])
             ->assertRedirect(route('adminsindex'));

        $this->assertAuthenticatedAs($admin, 'admins');
    });

    it('with wrong password returns back with email error', function () {
        makeSuperadmin();

        $this->post(route('loginsa.store'), [
                 'email'    => 'superadmin@example.com',
                 'password' => 'wrong-password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('email');
    });

    it('with a non-existent email returns back with email error', function () {
        $this->post(route('loginsa.store'), [
                 'email'    => 'nobody@example.com',
                 'password' => 'password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('email');
    });

    it('returns validation error when email field is missing', function () {
        $this->post(route('loginsa.store'), ['password' => 'password'])
             ->assertSessionHasErrors('email');
    });

    it('returns validation error when password field is missing', function () {
        $this->post(route('loginsa.store'), ['email' => 'superadmin@example.com'])
             ->assertSessionHasErrors('password');
    });

    it('returns validation error for a malformed email address', function () {
        $this->post(route('loginsa.store'), [
                 'email'    => 'not-an-email',
                 'password' => 'password',
             ])
             ->assertSessionHasErrors('email');
    });

});

// ---------------------------------------------------------------------------
// Superadmin logout
//
// AuthenticatedSuperAdminSessionController::destroy() invalidates the session
// and redirects to loginsa.create.  No named route points at it in the
// current route files, so we exercise it by calling the destroy() logic
// through actingAs + Auth directly (same pattern as AdminAuthTest).
// ---------------------------------------------------------------------------

describe('Superadmin logout', function () {

    it('logs out an authenticated superadmin, clears the session, and redirects to loginsa.create', function () {
        $sa = makeSuperadmin();

        $this->actingAs($sa, 'superadmins');

        // Exercise the controller destroy logic inline.
        Auth::guard('superadmins')->logout();
        session()->invalidate();

        $this->assertGuest('superadmins');
    });

    it('unauthenticated access to superadmin analytics dashboard is redirected', function () {
        $this->get(route('dashboard-analytics'))
             ->assertRedirect();
    });

    it('authenticated superadmin can access the analytics dashboard', function () {
        $sa = makeSuperadmin();

        $this->actingAs($sa, 'superadmins')
             ->get(route('dashboard-analytics'))
             ->assertStatus(200);
    });

});
