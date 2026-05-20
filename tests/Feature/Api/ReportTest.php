<?php

use App\Models\Activetype;
use App\Models\Business;
use App\Models\Category;
use App\Models\City;
use App\Models\Report;
use App\Models\Service;
use App\Models\Subcategory;
use App\Models\Superadmin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function reportUser(string $phone = '0967000001'): User
{
    return User::create([
        'name'      => 'Reporter',
        'phone'     => $phone,
        'password'  => Hash::make('password'),
        'is_active' => true,
    ]);
}

function reportService(User $owner): Service
{
    $city       = City::factory()->create();
    $activetype = Activetype::create(['name' => 'RepType' . uniqid()]);
    $business   = Business::create([
        'user_id'        => $owner->id,
        'city_id'        => $city->id,
        'activetype_id'  => $activetype->id,
        'license_number' => '22222',
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

    return Service::create([
        'business_id'    => $business->id,
        'category_id'    => $category->id,
        'subcategory_id' => $subcategory->id,
        'title'          => 'Reportable Service',
        'description'    => 'test description',
        'quantity'       => 5,
        'services_type'  => 'sale',
        'currency'       => 'USD',
        'status'         => 'approved',
        'price_usd'      => 80,
    ]);
}

// ── POST /api/report/{id} ─────────────────────────────────────────────────────

describe('POST /api/report/{id}', function () {
    it('submits a report for a service successfully', function () {
        $owner    = reportUser('0967000002');
        $service  = reportService($owner);
        $reporter = reportUser('0967000003');

        $token = $reporter->createToken('test')->plainTextToken;

        $this->actingAs($reporter, 'users')
             ->withToken($token)
             ->postJson("/api/report/{$service->id}", [
                 'reason'  => 'Spam',
                 'message' => 'This looks like a scam.',
             ]);

        $this->assertDatabaseHas('reports', [
            'user_id'    => $reporter->id,
            'service_id' => $service->id,
            'reason'     => 'Spam',
        ]);
    });

    it('returns 401 for unauthenticated user', function () {
        $owner   = reportUser('0967000004');
        $service = reportService($owner);

        $this->postJson("/api/report/{$service->id}", ['reason' => 'Fraud'])
             ->assertStatus(401);
    });

    it('does not allow a user to report their own service', function () {
        $owner   = reportUser('0967000005');
        $service = reportService($owner);

        $token = $owner->createToken('test')->plainTextToken;

        $response = $this->withToken($token)
             ->postJson("/api/report/{$service->id}", ['reason' => 'Spam']);

        // The controller returns back()->with('error') — a redirect, not JSON 4xx.
        // We verify no report row was inserted to confirm the guard worked.
        $this->assertDatabaseMissing('reports', [
            'user_id'    => $owner->id,
            'service_id' => $service->id,
        ]);
    });
});

// ── GET /api/reports (superadmin) ─────────────────────────────────────────────

describe('GET /api/reports', function () {
    it('returns a list of all reports for authenticated superadmin', function () {
        $owner    = reportUser('0967000006');
        $service  = reportService($owner);
        $reporter = reportUser('0967000007');

        Report::create([
            'user_id'    => $reporter->id,
            'service_id' => $service->id,
            'reason'     => 'Test',
            'status'     => 'pending',
        ]);

        $superadmin = Superadmin::factory()->create();

        $this->actingAs($superadmin, 'sanctum')
             ->getJson('/api/reports')
             ->assertStatus(200);
    });
});

// ── POST /api/updatereport/{id} ───────────────────────────────────────────────

describe('POST /api/updatereport/{id}', function () {
    it('updates report status to reviewed', function () {
        $owner    = reportUser('0967000008');
        $service  = reportService($owner);
        $reporter = reportUser('0967000009');

        $report = Report::create([
            'user_id'    => $reporter->id,
            'service_id' => $service->id,
            'reason'     => 'Spam',
            'status'     => 'pending',
        ]);

        $superadmin = Superadmin::factory()->create();

        $this->actingAs($superadmin, 'sanctum')
             ->postJson("/api/updatereport/{$report->id}", ['status' => 'reviewed'])
             ->assertRedirect();

        expect($report->fresh()->status)->toBe('reviewed');
    });
});

// ── DELETE /api/deletereport/{id} ─────────────────────────────────────────────

describe('DELETE /api/deletereport/{id}', function () {
    it('deletes a report', function () {
        $owner    = reportUser('0967000010');
        $service  = reportService($owner);
        $reporter = reportUser('0967000011');

        $report = Report::create([
            'user_id'    => $reporter->id,
            'service_id' => $service->id,
            'reason'     => 'Spam',
            'status'     => 'pending',
        ]);

        $superadmin = Superadmin::factory()->create();

        $this->actingAs($superadmin, 'sanctum')
             ->deleteJson("/api/deletereport/{$report->id}")
             ->assertRedirect();

        $this->assertDatabaseMissing('reports', ['id' => $report->id]);
    });
});
