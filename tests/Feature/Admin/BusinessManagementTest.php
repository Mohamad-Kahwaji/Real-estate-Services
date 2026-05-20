<?php

use App\Models\Admin;
use App\Models\Activetype;
use App\Models\Business;
use App\Models\City;
use App\Models\Superadmin;
use App\Models\User;
use App\Notifications\InvoiceCreated;
use App\Notifications\UserDatabaseNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Helpers
// ---------------------------------------------------------------------------

/**
 * Create a Superadmin that has both required permissions so the
 * approve/reject middleware is satisfied.
 */
function makeSuperadminWithPermissions(): Superadmin
{
    $superadmin = Superadmin::create([
        'name'     => 'Super Admin',
        'email'    => 'superadmin@example.com',
        'password' => bcrypt('password'),
    ]);

    // Ensure the spatie permission records exist for the 'superadmins' guard
    \Spatie\Permission\Models\Permission::firstOrCreate(
        ['name' => 'accept business account', 'guard_name' => 'superadmins']
    );
    \Spatie\Permission\Models\Permission::firstOrCreate(
        ['name' => 'reject business account', 'guard_name' => 'superadmins']
    );

    $superadmin->givePermissionTo(['accept business account', 'reject business account']);

    return $superadmin;
}

/**
 * Create a business with its required relations (owner user, city, activetype).
 */
function makeBusiness(string $status = 'pending'): Business
{
    $owner      = User::factory()->create();
    $city       = City::factory()->create();
    $activetype = Activetype::create(['name' => 'Contractor']);

    return Business::create([
        'user_id'        => $owner->id,
        'city_id'        => $city->id,
        'activetype_id'  => $activetype->id,
        'license_number' => 12345,
        'job_name_ar'    => 'اسم الوظيفة',
        'job_name_en'    => 'Job Name',
        'activites'      => 'Construction',
        'details'        => 'Some details',
        'status'         => $status,
    ]);
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------

describe('Business Management – Admin Panel', function () {

    it('returns 200 with business list when superadmin visits /business', function () {
        $superadmin = makeSuperadminWithPermissions();
        makeBusiness('pending');
        makeBusiness('approved');

        $response = $this
            ->actingAs($superadmin, 'superadmins')
            ->get('/business');

        $response->assertStatus(200);
    });

    it('approves a pending business and updates its status', function () {
        Notification::fake();

        $superadmin = makeSuperadminWithPermissions();
        $business   = makeBusiness('pending');

        $response = $this
            ->actingAs($superadmin, 'superadmins')
            ->post("/approve/{$business->id}");

        $response->assertRedirect()
                 ->assertSessionHas('success');

        expect($business->fresh()->status)->toBe('approved');
    });

    it('rejects a pending business and updates its status', function () {
        Notification::fake();

        $superadmin = makeSuperadminWithPermissions();
        $business   = makeBusiness('pending');

        $response = $this
            ->actingAs($superadmin, 'superadmins')
            ->post("/reject/{$business->id}");

        $response->assertRedirect()
                 ->assertSessionHas('success');

        expect($business->fresh()->status)->toBe('rejected');
    });

    it('sends an InvoiceCreated notification to the owner when a business is approved', function () {
        Notification::fake();

        $superadmin = makeSuperadminWithPermissions();
        $business   = makeBusiness('pending');

        $this
            ->actingAs($superadmin, 'superadmins')
            ->post("/approve/{$business->id}");

        Notification::assertSentTo(
            $business->user,
            InvoiceCreated::class,
            fn (InvoiceCreated $notification) =>
                $notification->data['business_id'] === $business->id
        );
    });

    it('sends a UserDatabaseNotification to the owner when a business is rejected', function () {
        Notification::fake();

        $superadmin = makeSuperadminWithPermissions();
        $business   = makeBusiness('pending');

        $this
            ->actingAs($superadmin, 'superadmins')
            ->post("/reject/{$business->id}");

        Notification::assertSentTo(
            $business->user,
            UserDatabaseNotification::class,
            fn (UserDatabaseNotification $n) =>
                $n->data['business_id'] === $business->id
        );
    });

    it('shows pending businesses count in the view at /business', function () {
        $superadmin = makeSuperadminWithPermissions();

        // 2 pending, 1 approved
        makeBusiness('pending');
        makeBusiness('pending');
        makeBusiness('approved');

        $response = $this
            ->actingAs($superadmin, 'superadmins')
            ->get('/business');

        $response->assertStatus(200);
        // The view receives a $businesses collection; pending ones must appear
        $response->assertViewHas('businesses', function ($businesses) {
            return $businesses->where('status', 'pending')->count() === 2;
        });
    });

    it('redirects unauthenticated visitors away from /business', function () {
        $this->get('/business')->assertRedirect();
    });

    it('denies an admin without the accept permission from approving a business', function () {
        Notification::fake();

        // Admin without the 'accept business account' permission
        $admin = Admin::create([
            'name'     => 'Plain Admin',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        $business = makeBusiness('pending');

        $response = $this
            ->actingAs($admin, 'admins')
            ->post("/approve/{$business->id}");

        // Spatie returns 403 when permission check fails
        $response->assertStatus(403);
        expect($business->fresh()->status)->toBe('pending');
    });
});
