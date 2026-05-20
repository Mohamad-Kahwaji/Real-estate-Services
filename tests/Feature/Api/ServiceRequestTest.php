<?php

use App\Models\Activetype;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ---------------------------------------------------------------------------
// Shared helpers
// ---------------------------------------------------------------------------

/**
 * Create a User that has an *approved* Business.
 */
function makeUserWithBusiness(): array
{
    $user       = User::factory()->create();
    $city       = City::factory()->create();
    $activetype = Activetype::create(['name' => 'Contractor']);

    $business = Business::create([
        'user_id'        => $user->id,
        'city_id'        => $city->id,
        'activetype_id'  => $activetype->id,
        'license_number' => 11111,
        'job_name_ar'    => 'شركة',
        'job_name_en'    => 'Company',
        'activites'      => 'Real estate',
        'details'        => 'Details here',
        'status'         => 'approved',
    ]);

    return compact('user', 'business');
}

/**
 * Create an approved Service belonging to a given Business.
 */
function makeApprovedService(Business $business): Service
{
    $category    = Category::factory()->create();
    $subcategory = Subcategory::create([
        'name_ar'     => 'فئة_فرعية',
        'name_en'     => 'subcategory',
        'category_id' => $category->id,
    ]);

    return Service::create([
        'business_id'    => $business->id,
        'category_id'    => $category->id,
        'subcategory_id' => $subcategory->id,
        'title'          => 'Test Service',
        'description'    => 'A service description',
        'quantity'       => 10,
        'services_type'  => 'sale',
        'currency'       => 'USD',
        'price_usd'      => 100.00,
        'status'         => 'approved',
    ]);
}

// ---------------------------------------------------------------------------
// Tests
// ---------------------------------------------------------------------------

describe('API Service Requests', function () {

    // ── POST /api/servicerequest/{id} ──────────────────────────────────────

    it('authenticated user with an approved business can request a service', function () {
        // Requester
        ['user' => $requester, 'business' => $requesterBusiness] = makeUserWithBusiness();

        // Service owner (different user)
        ['business' => $ownerBusiness] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $token = $requester->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson("/api/servicerequest/{$service->id}", [
                'quantity'  => 2,
                'needed_at' => now()->addDays(3)->toDateString(),
                'details'   => 'Need this soon',
            ]);

        $response->assertStatus(201)
                 ->assertJsonPath('status', true);

        $this->assertDatabaseHas('service_requests', [
            'user_id'    => $requester->id,
            'service_id' => $service->id,
            'status'     => 'pending',
        ]);
    });

    it('returns 401 when unauthenticated user tries to request a service', function () {
        ['business' => $ownerBusiness] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $this->postJson("/api/servicerequest/{$service->id}", [
            'quantity'  => 1,
            'needed_at' => now()->addDays(2)->toDateString(),
        ])->assertStatus(401);
    });

    it('prevents a user from requesting their own business service', function () {
        ['user' => $owner, 'business' => $ownerBusiness] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson("/api/servicerequest/{$service->id}", [
                'quantity'  => 1,
                'needed_at' => now()->addDays(2)->toDateString(),
            ]);

        $response->assertStatus(403)
                 ->assertJsonPath('status', false);
    });

    it('returns 403 when user has no approved business and tries to request a service', function () {
        $user    = User::factory()->create(); // no business
        $service = makeApprovedService(makeUserWithBusiness()['business']);

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->postJson("/api/servicerequest/{$service->id}", [
                 'quantity'  => 1,
                 'needed_at' => now()->addDays(2)->toDateString(),
             ])
             ->assertStatus(403);
    });

    it('returns 422 when requested quantity exceeds available stock', function () {
        ['user' => $requester] = makeUserWithBusiness();
        ['business' => $ownerBusiness] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness); // quantity = 10

        $token = $requester->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->postJson("/api/servicerequest/{$service->id}", [
                 'quantity'  => 999,
                 'needed_at' => now()->addDays(2)->toDateString(),
             ])
             ->assertStatus(422)
             ->assertJsonPath('status', false);
    });

    // ── GET /api/sentservice ───────────────────────────────────────────────

    it('returns sent service requests for the authenticated user', function () {
        ['user' => $requester, 'business' => $requesterBusiness] = makeUserWithBusiness();
        ['business' => $ownerBusiness] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        ServiceRequest::create([
            'user_id'        => $requester->id,
            'service_id'     => $service->id,
            'business_id'    => $ownerBusiness->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(2)->toDateString(),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $token = $requester->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->getJson('/api/sentservice');

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'data']);
    });

    it('returns 401 on GET /api/sentservice without auth', function () {
        $this->getJson('/api/sentservice')->assertStatus(401);
    });

    // ── GET /api/servicereceived ───────────────────────────────────────────

    it('returns received service requests for a business owner', function () {
        ['user' => $owner, 'business' => $ownerBusiness] = makeUserWithBusiness();
        ['user' => $requester, 'business' => $requesterBusiness] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        // A paid+pending request arrives for the owner's business
        ServiceRequest::create([
            'user_id'        => $requester->id,
            'service_id'     => $service->id,
            'business_id'    => $ownerBusiness->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(2)->toDateString(),
            'status'         => 'pending',
            'payment_status' => 'paid',
        ]);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->getJson('/api/servicereceived');

        $response->assertStatus(200)
                 ->assertJsonStructure(['message', 'data']);

        $data = $response->json('data');
        expect(count($data))->toBe(1);
    });

    // ── POST /api/acceptservice/{id} ──────────────────────────────────────

    it('business owner can accept a paid pending service request', function () {
        ['user' => $owner, 'business' => $ownerBusiness] = makeUserWithBusiness();
        ['user' => $requester] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $serviceRequest = ServiceRequest::create([
            'user_id'        => $requester->id,
            'service_id'     => $service->id,
            'business_id'    => $ownerBusiness->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(2)->toDateString(),
            'status'         => 'pending',
            'payment_status' => 'paid',
        ]);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson("/api/acceptservice/{$serviceRequest->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('status', true);

        expect($serviceRequest->fresh()->status)->toBe('approved');
    });

    it('cannot accept a service request that is unpaid', function () {
        ['user' => $owner, 'business' => $ownerBusiness] = makeUserWithBusiness();
        ['user' => $requester] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $serviceRequest = ServiceRequest::create([
            'user_id'        => $requester->id,
            'service_id'     => $service->id,
            'business_id'    => $ownerBusiness->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(2)->toDateString(),
            'status'         => 'pending',
            'payment_status' => 'unpaid',   // <-- not paid
        ]);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson("/api/acceptservice/{$serviceRequest->id}");

        // Controller uses firstOrFail — returns 404 when payment_status != 'paid'
        $response->assertStatus(404);
    });

    // ── POST /api/rejectservice/{id} ──────────────────────────────────────

    it('business owner can reject a pending service request', function () {
        ['user' => $owner, 'business' => $ownerBusiness] = makeUserWithBusiness();
        ['user' => $requester] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $serviceRequest = ServiceRequest::create([
            'user_id'        => $requester->id,
            'service_id'     => $service->id,
            'business_id'    => $ownerBusiness->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(2)->toDateString(),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this
            ->withToken($token)
            ->postJson("/api/rejectservice/{$serviceRequest->id}");

        $response->assertStatus(200)
                 ->assertJsonPath('status', true);

        expect($serviceRequest->fresh()->status)->toBe('rejected');
    });

    it('cannot reject a service request that belongs to another business', function () {
        ['user' => $intruder] = makeUserWithBusiness();
        ['user' => $owner, 'business' => $ownerBusiness] = makeUserWithBusiness();
        ['user' => $requester] = makeUserWithBusiness();
        $service = makeApprovedService($ownerBusiness);

        $serviceRequest = ServiceRequest::create([
            'user_id'        => $requester->id,
            'service_id'     => $service->id,
            'business_id'    => $ownerBusiness->id,
            'quantity'       => 1,
            'needed_at'      => now()->addDays(2)->toDateString(),
            'status'         => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $token = $intruder->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->postJson("/api/rejectservice/{$serviceRequest->id}")
             ->assertStatus(404);
    });
});
