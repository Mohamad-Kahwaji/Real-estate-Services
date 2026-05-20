<?php

use App\Models\Activetype;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Shared helpers
// ---------------------------------------------------------------------------

/**
 * Create a User that owns an approved Business.
 */
function serviceTestUser(array $overrides = []): array
{
    $user = User::create(array_merge([
        'name'      => 'Service Test User',
        'phone'     => '0912300001',
        'password'  => Hash::make('password'),
        'is_active' => true,
    ], $overrides));

    $city       = City::factory()->create();
    $activetype = Activetype::create(['name' => 'Contractor']);

    $business = Business::create([
        'user_id'        => $user->id,
        'city_id'        => $city->id,
        'activetype_id'  => $activetype->id,
        'license_number' => 22222,
        'job_name_ar'    => 'شركة',
        'job_name_en'    => 'Company',
        'activites'      => 'Real estate',
        'details'        => 'Test details',
        'status'         => 'approved',
    ]);

    return compact('user', 'business');
}

/**
 * Create an approved Service for a given Business.
 */
function serviceTestService(Business $business): Service
{
    $category    = Category::factory()->create();
    $subcategory = Subcategory::create([
        'name_ar'     => 'فرعية',
        'name_en'     => 'sub',
        'category_id' => $category->id,
    ]);

    return Service::create([
        'business_id'    => $business->id,
        'category_id'    => $category->id,
        'subcategory_id' => $subcategory->id,
        'title'          => 'Test Service',
        'description'    => 'A test service',
        'quantity'       => 5,
        'services_type'  => 'sale',
        'currency'       => 'USD',
        'price_usd'      => 200.00,
        'price_syp'      => 1000000.00,
        'status'         => 'approved',
    ]);
}

// ---------------------------------------------------------------------------
// GET /api/allservices
// ---------------------------------------------------------------------------

describe('GET /api/allservices', function () {

    it('returns 200 with a JSON list of approved services', function () {
        ['user' => $owner, 'business' => $business] = serviceTestUser();

        // Create a second user to view the owner's service (allservices excludes own business)
        $viewer = User::create([
            'name'      => 'Viewer',
            'phone'     => '0912300002',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        serviceTestService($business);

        $token = $viewer->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->getJson('/api/allservices')
             ->assertStatus(200)
             ->assertJsonStructure(['status', 'data'])
             ->assertJson(['status' => true]);
    });

    it('returns 401 when called without authentication', function () {
        $this->getJson('/api/allservices')
             ->assertStatus(401);
    });

    it('data array contains the expected service', function () {
        ['user' => $owner, 'business' => $business] = serviceTestUser();

        $viewer = User::create([
            'name'      => 'Viewer2',
            'phone'     => '0912300003',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $service = serviceTestService($business);
        $token   = $viewer->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/allservices');

        $ids = collect($response->json('data'))->pluck('id');
        expect($ids)->toContain($service->id);
    });

});

// ---------------------------------------------------------------------------
// GET /api/myservices
// ---------------------------------------------------------------------------

describe('GET /api/myservices', function () {

    it('returns 401 when unauthenticated', function () {
        $this->getJson('/api/myservices')
             ->assertStatus(401);
    });

    it('returns 200 for authenticated user with an approved business', function () {
        ['user' => $user] = serviceTestUser();

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->getJson('/api/myservices')
             ->assertStatus(200)
             ->assertJsonStructure(['status', 'data'])
             ->assertJson(['status' => true]);
    });

    it('returns only services belonging to the authenticated user', function () {
        ['user' => $owner, 'business' => $ownerBusiness]     = serviceTestUser();
        ['user' => $other, 'business' => $otherBusiness]     = serviceTestUser([
            'phone' => '0912300099',
        ]);

        $myService    = serviceTestService($ownerBusiness);
        $otherService = serviceTestService($otherBusiness);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->getJson('/api/myservices');

        $ids = collect($response->json('data'))->pluck('id');

        expect($ids)->toContain($myService->id)
                    ->not->toContain($otherService->id);
    });

    it('returns 404 when authenticated user has no business', function () {
        $user = User::create([
            'name'      => 'No Business',
            'phone'     => '0912300010',
            'password'  => Hash::make('password'),
            'is_active' => true,
        ]);

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->getJson('/api/myservices')
             ->assertStatus(404);
    });

});

// ---------------------------------------------------------------------------
// GET /api/services/{id}  (resource show — authenticated)
// ---------------------------------------------------------------------------

describe('GET /api/services/{id}', function () {

    it('returns 200 with service data for an existing service', function () {
        ['user' => $owner, 'business' => $business] = serviceTestUser();
        $service = serviceTestService($business);

        $token = $owner->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->getJson("/api/services/{$service->id}")
             ->assertStatus(200);
    });

    it('returns 404 for a non-existent service id', function () {
        ['user' => $user] = serviceTestUser();
        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->getJson('/api/services/99999')
             ->assertStatus(404);
    });

});
