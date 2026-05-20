<?php

use App\Models\City;
use App\Models\Superadmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

// ── Helpers ──────────────────────────────────────────────────────────────────

function superadmin(): Superadmin
{
    return Superadmin::factory()->create();
}

function cityPayload(array $overrides = []): array
{
    return array_merge(['name_ar' => 'بيروت', 'name_en' => 'Beirut'], $overrides);
}

// ── Index ─────────────────────────────────────────────────────────────────────

describe('GET /cities', function () {
    it('returns 200 for authenticated superadmin', function () {
        actingAs(superadmin(), 'superadmins')
            ->get(route('cities.index'))
            ->assertStatus(200);
    });

    it('redirects guests to login', function () {
        $this->get(route('cities.index'))
            ->assertRedirect();
    });

    it('lists existing cities', function () {
        City::factory()->count(3)->create();
        actingAs(superadmin(), 'superadmins')
            ->get(route('cities.index'))
            ->assertStatus(200)
            ->assertSee(City::first()->name_en);
    });
});

// ── Store ─────────────────────────────────────────────────────────────────────

describe('POST /cities', function () {
    it('creates a city and redirects with success', function () {
        actingAs(superadmin(), 'superadmins')
            ->post(route('cities.store'), cityPayload())
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cities', ['name_en' => 'Beirut']);
    });

    it('rejects missing name_en', function () {
        actingAs(superadmin(), 'superadmins')
            ->post(route('cities.store'), ['name_ar' => 'بيروت'])
            ->assertSessionHasErrors('name_en');
    });

    it('rejects missing name_ar', function () {
        actingAs(superadmin(), 'superadmins')
            ->post(route('cities.store'), ['name_en' => 'Beirut'])
            ->assertSessionHasErrors('name_ar');
    });
});

// ── Update ────────────────────────────────────────────────────────────────────

describe('PUT /cities/{id}', function () {
    it('updates city names', function () {
        $city = City::factory()->create();

        actingAs(superadmin(), 'superadmins')
            ->put(route('cities.update', $city->id), ['name_ar' => 'دمشق', 'name_en' => 'Damascus'])
            ->assertRedirect()
            ->assertSessionHas('success');

        expect($city->fresh()->name_en)->toBe('Damascus');
    });
});

// ── Destroy ───────────────────────────────────────────────────────────────────

describe('DELETE /cities/{id}', function () {
    it('deletes the city from database', function () {
        $city = City::factory()->create();

        actingAs(superadmin(), 'superadmins')
            ->delete(route('cities.destroy', $city->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('cities', ['id' => $city->id]);
    });
});
