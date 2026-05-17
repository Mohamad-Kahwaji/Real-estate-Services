<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ── Real Estate ───────────────────────────────
            ['name_ar' => 'سكني',                'name_en' => 'Residential'],
            ['name_ar' => 'تجاري',               'name_en' => 'Commercial'],
            ['name_ar' => 'أراضي',               'name_en' => 'Land & Plots'],
            ['name_ar' => 'خدمات عقارية',        'name_en' => 'Real Estate Services'],
            ['name_ar' => 'ترميم وصيانة',        'name_en' => 'Renovation & Maintenance'],

            // ── Home & Property Services ──────────────────
            ['name_ar' => 'الصيانة',             'name_en' => 'Maintenance'],
            ['name_ar' => 'التنظيف',             'name_en' => 'Cleaning'],
            ['name_ar' => 'النقل',               'name_en' => 'Moving & Transport'],
            ['name_ar' => 'التشطيبات',           'name_en' => 'Finishing & Construction'],
            ['name_ar' => 'الأنظمة والتقنيات',   'name_en' => 'Systems & Technology'],
            ['name_ar' => 'الخدمات المنزلية',    'name_en' => 'Home Services'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name_en' => $cat['name_en']], $cat);
        }
    }
}
