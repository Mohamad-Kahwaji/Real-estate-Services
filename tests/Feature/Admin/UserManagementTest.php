<?php

use App\Models\Activetype;
use App\Models\Business;
use App\Models\City;
use App\Models\Superadmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

function umSuperadmin(): Superadmin
{
    return Superadmin::factory()->create();
}

function umUser(array $attrs = []): User
{
    return User::create(array_merge([
        'name'      => 'Test User',
        'phone'     => '0912345678',
        'password'  => Hash::make('password'),
        'is_active' => true,
    ], $attrs));
}

// ── Index ─────────────────────────────────────────────────────────────────────

describe('GET /allindex', function () {
    it('returns 200 and lists users for superadmin', function () {
        umUser();
        actingAs(umSuperadmin(), 'superadmins')
            ->get(route('allindex.index'))
            ->assertStatus(200);
    });

    it('redirects unauthenticated request', function () {
        $this->get(route('allindex.index'))->assertRedirect();
    });
});

// ── Toggle Status ─────────────────────────────────────────────────────────────

describe('POST /users/{id}/toggle-status', function () {
    it('suspends an active user', function () {
        $user = umUser(['is_active' => true]);

        actingAs(umSuperadmin(), 'superadmins')
            ->post(route('users.toggle-status', $user->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        expect($user->fresh()->is_active)->toBeFalse();
    });

    it('activates a suspended user', function () {
        $user = umUser(['is_active' => false]);

        actingAs(umSuperadmin(), 'superadmins')
            ->post(route('users.toggle-status', $user->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        expect($user->fresh()->is_active)->toBeTrue();
    });

    it('suspends approved businesses when user is suspended', function () {
        $user       = umUser(['is_active' => true]);
        $city       = City::factory()->create();
        $activetype = Activetype::create(['name' => 'UMType']);
        $business   = Business::create([
            'user_id'        => $user->id,
            'city_id'        => $city->id,
            'activetype_id'  => $activetype->id,
            'license_number' => '55555',
            'activites'      => 'test',
            'details'        => 'test',
            'status'         => 'approved',
            'job_name_ar'    => 'عقارات',
            'job_name_en'    => 'Real Estate',
        ]);

        actingAs(umSuperadmin(), 'superadmins')
            ->post(route('users.toggle-status', $user->id));

        expect($business->fresh()->status)->toBe('suspended');
    });

    it('returns 404 for non-existent user', function () {
        actingAs(umSuperadmin(), 'superadmins')
            ->post(route('users.toggle-status', 99999))
            ->assertStatus(404);
    });
});
