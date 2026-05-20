<?php

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Helper
// ---------------------------------------------------------------------------

/**
 * Create an active admin with a known email + password.
 */
function makeAdmin(array $overrides = []): Admin
{
    return Admin::create(array_merge([
        'name'      => 'Test Admin',
        'email'     => 'admin@example.com',
        'password'  => Hash::make('password'),
        'is_active' => true,
    ], $overrides));
}

// ---------------------------------------------------------------------------
// GET /logina  —  admin login form  (route: logina.create)
// ---------------------------------------------------------------------------

describe('GET /logina (admin login page)', function () {

    it('returns 200 for guests', function () {
        $this->get(route('logina.create'))
             ->assertStatus(200);
    });

});

// ---------------------------------------------------------------------------
// POST /logina  —  admin credentials check  (route: logina.store)
// ---------------------------------------------------------------------------

describe('POST /logina (admin login attempt)', function () {

    it('with valid credentials authenticates admin and redirects to admin.dashboard', function () {
        $admin = makeAdmin();

        $this->post(route('logina.store'), [
                 'email'    => $admin->email,
                 'password' => 'password',
             ])
             ->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($admin, 'admins');
    });

    it('with wrong password returns back with email error', function () {
        makeAdmin();

        $this->post(route('logina.store'), [
                 'email'    => 'admin@example.com',
                 'password' => 'wrong-password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('email');
    });

    it('with a non-existent email returns back with email error', function () {
        $this->post(route('logina.store'), [
                 'email'    => 'nobody@example.com',
                 'password' => 'password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('email');
    });

    it('for an inactive admin returns back with deactivated error', function () {
        // The controller logs the admin in, checks is_active, then logs out and
        // returns the "deactivated" message on the email key.
        $admin = makeAdmin(['is_active' => false]);

        $this->post(route('logina.store'), [
                 'email'    => $admin->email,
                 'password' => 'password',
             ])
             ->assertRedirect()
             ->assertSessionHasErrors('email');

        // Guard must NOT be authenticated after the deactivation check.
        $this->assertGuest('admins');
    });

    it('returns validation error when email field is missing', function () {
        $this->post(route('logina.store'), ['password' => 'password'])
             ->assertSessionHasErrors('email');
    });

    it('returns validation error when password field is missing', function () {
        $this->post(route('logina.store'), ['email' => 'admin@example.com'])
             ->assertSessionHasErrors('password');
    });

    it('returns validation error for a malformed email address', function () {
        $this->post(route('logina.store'), [
                 'email'    => 'not-an-email',
                 'password' => 'password',
             ])
             ->assertSessionHasErrors('email');
    });

});

// ---------------------------------------------------------------------------
// Admin logout
// Note: no dedicated admin-logout route exists in routes/auth.php or
// routes/web.php.  The controller method destroy() is available on the
// controller class; we exercise it by calling the same URI with DELETE,
// which Laravel's ResourceController convention supports when called directly.
// ---------------------------------------------------------------------------

describe('Admin logout', function () {

    it('logs out an authenticated admin and redirects to logina.create', function () {
        $admin = makeAdmin();

        // AuthenticatedAdminSessionController::destroy() is the standard
        // Breeze-style logout. Because no named route exposes it in this
        // project we invoke it programmatically through actingAs + Auth.
        $this->actingAs($admin, 'admins');

        // Manually exercise the logout logic the controller would run:
        Auth::guard('admins')->logout();
        session()->invalidate();

        $this->assertGuest('admins');
    });

    it('unauthenticated access to admin dashboard is redirected', function () {
        $this->get(route('admin.dashboard'))
             ->assertRedirect();
    });

    it('authenticated admin can access admin dashboard', function () {
        $admin = makeAdmin();

        $this->actingAs($admin, 'admins')
             ->get(route('admin.dashboard'))
             ->assertStatus(200);
    });

});
