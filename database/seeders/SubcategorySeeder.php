<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            // ── Real Estate ───────────────────────────────────────────────────
            'Residential' => [
                ['name_ar' => 'شقق',             'name_en' => 'Apartments'],
                ['name_ar' => 'فلل',             'name_en' => 'Villas'],
                ['name_ar' => 'منازل',           'name_en' => 'Houses'],
                ['name_ar' => 'استوديو',         'name_en' => 'Studio'],
            ],
            'Commercial' => [
                ['name_ar' => 'مكاتب',           'name_en' => 'Offices'],
                ['name_ar' => 'محلات تجارية',    'name_en' => 'Shops'],
                ['name_ar' => 'مستودعات',        'name_en' => 'Warehouses'],
            ],
            'Land & Plots' => [
                ['name_ar' => 'أراضي سكنية',     'name_en' => 'Residential Land'],
                ['name_ar' => 'أراضي زراعية',    'name_en' => 'Agricultural Land'],
                ['name_ar' => 'أراضي تجارية',    'name_en' => 'Commercial Land'],
            ],
            'Real Estate Services' => [
                ['name_ar' => 'وساطة عقارية',    'name_en' => 'Brokerage'],
                ['name_ar' => 'تقييم عقاري',     'name_en' => 'Property Valuation'],
                ['name_ar' => 'استشارات عقارية', 'name_en' => 'Real Estate Consulting'],
            ],
            'Renovation & Maintenance' => [
                ['name_ar' => 'طلاء وديكور',     'name_en' => 'Painting & Decor'],
                ['name_ar' => 'سباكة',           'name_en' => 'Plumbing'],
                ['name_ar' => 'أعمال كهربائية',  'name_en' => 'Electrical Work'],
                ['name_ar' => 'تصميم داخلي',     'name_en' => 'Interior Design'],
            ],

            // ── Maintenance ───────────────────────────────────────────────────
            'Maintenance' => [
                ['name_ar' => 'كهربائي',         'name_en' => 'Electrician'],
                ['name_ar' => 'سباك',            'name_en' => 'Plumber'],
                ['name_ar' => 'صيانة تكييف',     'name_en' => 'AC Maintenance'],
                ['name_ar' => 'صيانة غسالات',    'name_en' => 'Washing Machine Repair'],
                ['name_ar' => 'صيانة برادات',    'name_en' => 'Refrigerator Repair'],
                ['name_ar' => 'صيانة أفران',     'name_en' => 'Oven Repair'],
                ['name_ar' => 'صيانة مصاعد',     'name_en' => 'Elevator Maintenance'],
            ],

            // ── Cleaning ──────────────────────────────────────────────────────
            'Cleaning' => [
                ['name_ar' => 'تنظيف منازل',              'name_en' => 'Home Cleaning'],
                ['name_ar' => 'تنظيف مكاتب',              'name_en' => 'Office Cleaning'],
                ['name_ar' => 'تنظيف سجاد',               'name_en' => 'Carpet Cleaning'],
                ['name_ar' => 'تنظيف خزانات مياه',        'name_en' => 'Water Tank Cleaning'],
                ['name_ar' => 'تنظيف واجهات زجاجية',      'name_en' => 'Glass Facade Cleaning'],
            ],

            // ── Moving & Transport ────────────────────────────────────────────
            'Moving & Transport' => [
                ['name_ar' => 'نقل أثاث',        'name_en' => 'Furniture Moving'],
                ['name_ar' => 'نقل بضائع',       'name_en' => 'Goods Transport'],
                ['name_ar' => 'تحميل وتنزيل',    'name_en' => 'Loading & Unloading'],
                ['name_ar' => 'تغليف أثاث',      'name_en' => 'Furniture Packing'],
            ],

            // ── Finishing & Construction ──────────────────────────────────────
            'Finishing & Construction' => [
                ['name_ar' => 'دهان',            'name_en' => 'Painting'],
                ['name_ar' => 'جبصين',           'name_en' => 'Gypsum Work'],
                ['name_ar' => 'تركيب بلاط',      'name_en' => 'Tiling'],
                ['name_ar' => 'نجارة',           'name_en' => 'Carpentry'],
                ['name_ar' => 'ألمنيوم',         'name_en' => 'Aluminum Work'],
                ['name_ar' => 'حدادة',           'name_en' => 'Metal Work'],
            ],

            // ── Systems & Technology ──────────────────────────────────────────
            'Systems & Technology' => [
                ['name_ar' => 'تركيب كاميرات مراقبة', 'name_en' => 'CCTV Installation'],
                ['name_ar' => 'أنظمة إنذار',           'name_en' => 'Alarm Systems'],
                ['name_ar' => 'تمديد شبكات إنترنت',    'name_en' => 'Network Installation'],
                ['name_ar' => 'منزل ذكي',              'name_en' => 'Smart Home'],
            ],

            // ── Home Services ─────────────────────────────────────────────────
            'Home Services' => [
                ['name_ar' => 'جلي بلاط',        'name_en' => 'Floor Polishing'],
                ['name_ar' => 'مكافحة حشرات',    'name_en' => 'Pest Control'],
                ['name_ar' => 'غسيل سيارات',     'name_en' => 'Car Wash'],
                ['name_ar' => 'تنسيق حدائق',     'name_en' => 'Landscaping'],
                ['name_ar' => 'قص أشجار',        'name_en' => 'Tree Trimming'],
            ],
        ];

        foreach ($map as $categoryName => $subcategories) {
            $category = Category::where('name_en', $categoryName)->first();
            if (!$category) continue;

            foreach ($subcategories as $sub) {
                Subcategory::firstOrCreate(
                    ['name_en' => $sub['name_en'], 'category_id' => $category->id],
                    array_merge($sub, ['category_id' => $category->id])
                );
            }
        }
    }
}
