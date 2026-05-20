<?php

use App\Models\Activetype;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Subcategory;
use App\Models\Superadmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function dashSa(): Superadmin
{
    return Superadmin::factory()->create();
}

// ── GET /dash  ────────────────────────────────────────────────────────────────

describe('GET /dash (superadmin dashboard)', function () {
    it('returns 200 for an authenticated superadmin', function () {
        $this->actingAs(dashSa(), 'superadmins')
             ->get(route('dash'))
             ->assertStatus(200);
    });

    it('redirects unauthenticated visitors to login', function () {
        $this->get(route('dash'))->assertRedirect();
    });

    it('passes required view variables to the dashboard', function () {
        $this->actingAs(dashSa(), 'superadmins')
             ->get(route('dash'))
             ->assertViewHasAll([
                 'totalUsers',
                 'totalAdmins',
                 'totalBusinesses',
                 'totalServices',
                 'totalReports',
                 'totalRequests',
                 'totalCategories',
                 'totalSubcategories',
                 'totalCities',
             ]);
    });
});

// ── GET /indexsuperadmin  ────────────────────────────────────────────────────

describe('GET /indexsuperadmin (superadmin index alias)', function () {
    it('returns 200 for an authenticated superadmin', function () {
        $this->actingAs(dashSa(), 'superadmins')
             ->get(route('indexsuperadmin'))
             ->assertStatus(200);
    });

    it('redirects guests', function () {
        $this->get(route('indexsuperadmin'))->assertRedirect();
    });
});

// ── GET /adminsindex  ─────────────────────────────────────────────────────────

describe('GET /adminsindex', function () {
    it('returns 200 for a superadmin', function () {
        $this->actingAs(dashSa(), 'superadmins')
             ->get(route('adminsindex'))
             ->assertStatus(200);
    });

    it('redirects unauthenticated request', function () {
        $this->get(route('adminsindex'))->assertRedirect();
    });
});

// ── GET /superadmin/service-requests  ────────────────────────────────────────

describe('GET /superadmin/service-requests', function () {
    it('returns 200 with service requests list', function () {
        $this->actingAs(dashSa(), 'superadmins')
             ->get(route('superadmin.service-requests'))
             ->assertStatus(200);
    });

    it('redirects unauthenticated request', function () {
        $this->get(route('superadmin.service-requests'))->assertRedirect();
    });
});

// ── POST /superadmin/service-requests/{id}/approve  ──────────────────────────

describe('POST /superadmin/service-requests/{id}/approve', function () {
    it('approves a service request and redirects with success', function () {
        $user       = User::factory()->create();
        $city       = City::factory()->create();
        $activetype = Activetype::create(['name' => 'DashApprove']);
        $business   = Business::create([
            'user_id'        => $user->id,
            'city_id'        => $city->id,
            'activetype_id'  => $activetype->id,
            'license_number' => '33333',
            'activites'      => 'test',
            'details'        => 'test',
            'status'         => 'approved',
            'job_name_ar'    => 'شركة',
            'job_name_en'    => 'Company',
        ]);
        $category    = Category::factory()->create();
        $subcategory = Subcategory::create([
            'name_ar'     => 'فرعية',
            'name_en'     => 'sub',
            'category_id' => $category->id,
        ]);
        $service = Service::create([
            'business_id'    => $business->id,
            'category_id'    => $category->id,
            'subcategory_id' => $subcategory->id,
            'title'          => 'Svc',
            'description'    => 'test',
            'quantity'       => 5,
            'services_type'  => 'sale',
            'currency'       => 'USD',
            'status'         => 'approved',
            'price_usd'      => 10,
        ]);

        $requester = User::factory()->create();
        $sr = ServiceRequest::create([
            'user_id'        => $requester->id,
            'business_id'    => $business->id,
            'service_id'     => $service->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(3),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs(dashSa(), 'superadmins')
             ->post(route('superadmin.service-requests.approve', $sr->id))
             ->assertRedirect()
             ->assertSessionHas('success');

        expect($sr->fresh()->status)->toBe('approved');
    });

    it('returns 404 for a non-existent service request', function () {
        $this->actingAs(dashSa(), 'superadmins')
             ->post(route('superadmin.service-requests.approve', 99999))
             ->assertStatus(404);
    });
});

// ── POST /superadmin/service-requests/{id}/reject  ───────────────────────────

describe('POST /superadmin/service-requests/{id}/reject', function () {
    it('rejects a service request and redirects with success', function () {
        $user       = User::factory()->create();
        $city       = City::factory()->create();
        $activetype = Activetype::create(['name' => 'DashReject']);
        $business   = Business::create([
            'user_id'        => $user->id,
            'city_id'        => $city->id,
            'activetype_id'  => $activetype->id,
            'license_number' => '44444',
            'activites'      => 'test',
            'details'        => 'test',
            'status'         => 'approved',
            'job_name_ar'    => 'شركة',
            'job_name_en'    => 'Company',
        ]);
        $category    = Category::factory()->create();
        $subcategory = Subcategory::create([
            'name_ar'     => 'فرعية',
            'name_en'     => 'sub',
            'category_id' => $category->id,
        ]);
        $service = Service::create([
            'business_id'    => $business->id,
            'category_id'    => $category->id,
            'subcategory_id' => $subcategory->id,
            'title'          => 'Svc',
            'description'    => 'test',
            'quantity'       => 5,
            'services_type'  => 'sale',
            'currency'       => 'USD',
            'status'         => 'approved',
            'price_usd'      => 10,
        ]);

        $requester = User::factory()->create();
        $sr = ServiceRequest::create([
            'user_id'        => $requester->id,
            'business_id'    => $business->id,
            'service_id'     => $service->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(3),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $this->actingAs(dashSa(), 'superadmins')
             ->post(route('superadmin.service-requests.reject', $sr->id))
             ->assertRedirect()
             ->assertSessionHas('success');

        expect($sr->fresh()->status)->toBe('rejected');
    });
});
