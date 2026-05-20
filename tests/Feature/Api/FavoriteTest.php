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

// ── Helpers ───────────────────────────────────────────────────────────────────

function favUser(string $phone = '0956000001'): User
{
    return User::create([
        'name'      => 'Fav User',
        'phone'     => $phone,
        'password'  => Hash::make('password'),
        'is_active' => true,
    ]);
}

function favService(): Service
{
    $owner      = favUser('0956000099');
    $city       = City::factory()->create();
    $activetype = Activetype::create(['name' => 'FavType' . uniqid()]);
    $business   = Business::create([
        'user_id'        => $owner->id,
        'city_id'        => $city->id,
        'activetype_id'  => $activetype->id,
        'license_number' => '11111',
        'activites'      => 'test',
        'details'        => 'test',
        'status'         => 'approved',
        'job_name_ar'    => 'عقارات',
        'job_name_en'    => 'Real Estate',
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
        'title'          => 'Fav Service',
        'description'    => 'test description',
        'quantity'       => 5,
        'services_type'  => 'sale',
        'currency'       => 'USD',
        'status'         => 'approved',
        'price_usd'      => 50,
    ]);
}

// ── POST /api/favorite/{id}  (toggle) ─────────────────────────────────────────

describe('POST /api/favorite/{id}', function () {
    it('adds a service to favorites when not already favorited', function () {
        $user    = favUser();
        $service = favService();

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->postJson("/api/favorite/{$service->id}");

        $this->assertDatabaseHas('favorites', [
            'user_id'    => $user->id,
            'service_id' => $service->id,
        ]);
    });

    it('removes a service from favorites when already favorited', function () {
        $user    = favUser();
        $service = favService();

        $user->favorites()->attach($service->id, ['favorite' => 1]);

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)
             ->postJson("/api/favorite/{$service->id}");

        $this->assertDatabaseMissing('favorites', [
            'user_id'    => $user->id,
            'service_id' => $service->id,
        ]);
    });

    it('calling toggle twice leaves the service unfavorited', function () {
        $user    = favUser();
        $service = favService();

        $token = $user->createToken('test')->plainTextToken;

        $this->withToken($token)->postJson("/api/favorite/{$service->id}");
        $this->withToken($token)->postJson("/api/favorite/{$service->id}");

        $this->assertDatabaseMissing('favorites', [
            'user_id'    => $user->id,
            'service_id' => $service->id,
        ]);
    });

    it('returns 401 for unauthenticated request', function () {
        $service = favService();

        $this->postJson("/api/favorite/{$service->id}")
             ->assertStatus(401);
    });
});
