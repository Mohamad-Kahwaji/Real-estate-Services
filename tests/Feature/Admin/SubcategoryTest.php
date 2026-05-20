<?php

use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Superadmin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use function Pest\Laravel\actingAs;

uses(TestCase::class, RefreshDatabase::class);

// ── Helpers ───────────────────────────────────────────────────────────────────

function subSa(): Superadmin
{
    return Superadmin::factory()->create();
}

function subCategory(): Category
{
    return Category::factory()->create();
}

// ── Index ─────────────────────────────────────────────────────────────────────

describe('GET /subcategories', function () {
    it('returns 200 for authenticated superadmin', function () {
        actingAs(subSa(), 'superadmins')
            ->get(route('subcategories.index'))
            ->assertStatus(200);
    });

    it('redirects unauthenticated users', function () {
        $this->get(route('subcategories.index'))->assertRedirect();
    });
});

// ── Store ─────────────────────────────────────────────────────────────────────

describe('POST /subcategories', function () {
    it('creates a subcategory with valid data', function () {
        $category = subCategory();

        actingAs(subSa(), 'superadmins')
            ->post(route('subcategories.store'), [
                'name_ar'     => 'شقق',
                'name_en'     => 'Apartments',
                'category_id' => $category->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subcategories', ['name_en' => 'Apartments']);
    });

    it('rejects missing name_en', function () {
        $category = subCategory();

        actingAs(subSa(), 'superadmins')
            ->post(route('subcategories.store'), [
                'name_ar'     => 'شقق',
                'category_id' => $category->id,
            ])
            ->assertSessionHasErrors('name_en');
    });

    it('rejects missing name_ar', function () {
        $category = subCategory();

        actingAs(subSa(), 'superadmins')
            ->post(route('subcategories.store'), [
                'name_en'     => 'Apartments',
                'category_id' => $category->id,
            ])
            ->assertSessionHasErrors('name_ar');
    });

    it('rejects missing category_id', function () {
        actingAs(subSa(), 'superadmins')
            ->post(route('subcategories.store'), [
                'name_ar' => 'شقق',
                'name_en' => 'Apartments',
            ])
            ->assertSessionHasErrors('category_id');
    });

    it('rejects a non-existent category_id', function () {
        actingAs(subSa(), 'superadmins')
            ->post(route('subcategories.store'), [
                'name_ar'     => 'شقق',
                'name_en'     => 'Apartments',
                'category_id' => 99999,
            ])
            ->assertSessionHasErrors('category_id');
    });

    it('creates a subcategory with dynamic fields', function () {
        $category = subCategory();

        actingAs(subSa(), 'superadmins')
            ->post(route('subcategories.store'), [
                'name_ar'     => 'فلل',
                'name_en'     => 'Villas',
                'category_id' => $category->id,
                'fields'      => [
                    [
                        'name'        => 'area_sqm',
                        'label_ar'    => 'المساحة',
                        'label_en'    => 'Area',
                        'type'        => 'number',
                        'is_required' => '1',
                    ],
                ],
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('subcategories', ['name_en' => 'Villas']);
        $this->assertDatabaseHas('dynamic_fields', ['name' => 'area_sqm']);
    });
});

// ── Update ────────────────────────────────────────────────────────────────────

describe('PUT /subcategories/{id}', function () {
    it('updates subcategory names and category', function () {
        $category    = subCategory();
        $subcategory = Subcategory::create([
            'name_ar'     => 'قديم',
            'name_en'     => 'Old',
            'category_id' => $category->id,
        ]);

        actingAs(subSa(), 'superadmins')
            ->put(route('subcategories.update', $subcategory->id), [
                'name_ar'     => 'جديد',
                'name_en'     => 'New',
                'category_id' => $category->id,
            ])
            ->assertRedirect()
            ->assertSessionHas('success');

        expect($subcategory->fresh()->name_en)->toBe('New');
    });

    it('returns 404 for a non-existent subcategory', function () {
        $category = subCategory();

        actingAs(subSa(), 'superadmins')
            ->put(route('subcategories.update', 99999), [
                'name_ar'     => 'جديد',
                'name_en'     => 'New',
                'category_id' => $category->id,
            ])
            ->assertStatus(404);
    });
});

// ── Destroy ───────────────────────────────────────────────────────────────────

describe('DELETE /subcategories/{id}', function () {
    it('deletes the subcategory', function () {
        $category    = subCategory();
        $subcategory = Subcategory::create([
            'name_ar'     => 'للحذف',
            'name_en'     => 'ToDelete',
            'category_id' => $category->id,
        ]);

        actingAs(subSa(), 'superadmins')
            ->delete(route('subcategories.destroy', $subcategory->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('subcategories', ['id' => $subcategory->id]);
    });
});
