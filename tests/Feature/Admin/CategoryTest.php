<?php

use App\Models\Category;
use App\Models\Superadmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

function sa(): Superadmin
{
    return Superadmin::factory()->create();
}

// ── Index ─────────────────────────────────────────────────────────────────────

describe('GET /categories', function () {
    it('returns 200 for authenticated superadmin', function () {
        actingAs(sa(), 'superadmins')
            ->get(route('categories.index'))
            ->assertStatus(200);
    });

    it('redirects unauthenticated users', function () {
        $this->get(route('categories.index'))->assertRedirect();
    });
});

// ── Store ─────────────────────────────────────────────────────────────────────

describe('POST /categories', function () {
    it('creates a category with valid data', function () {
        actingAs(sa(), 'superadmins')
            ->post(route('categories.store'), ['name_ar' => 'سكني', 'name_en' => 'Residential'])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('categories', ['name_en' => 'Residential']);
    });

    it('rejects missing name_en', function () {
        actingAs(sa(), 'superadmins')
            ->post(route('categories.store'), ['name_ar' => 'سكني'])
            ->assertSessionHasErrors('name_en');
    });

    it('rejects missing name_ar', function () {
        actingAs(sa(), 'superadmins')
            ->post(route('categories.store'), ['name_en' => 'Residential'])
            ->assertSessionHasErrors('name_ar');
    });
});

// ── Update ────────────────────────────────────────────────────────────────────

describe('PUT /categories/{id}', function () {
    it('updates category names', function () {
        $category = Category::factory()->create();

        actingAs(sa(), 'superadmins')
            ->put(route('categories.update', $category->id), ['name_ar' => 'تجاري', 'name_en' => 'Commercial'])
            ->assertRedirect()
            ->assertSessionHas('success');

        expect($category->fresh()->name_en)->toBe('Commercial');
    });
});

// ── Destroy ───────────────────────────────────────────────────────────────────

describe('DELETE /categories/{id}', function () {
    it('deletes the category', function () {
        $category = Category::factory()->create();

        actingAs(sa(), 'superadmins')
            ->delete(route('categories.destroy', $category->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    });
});
