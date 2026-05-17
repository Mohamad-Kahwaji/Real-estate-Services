<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DynamicField;
use App\Models\Service;
use App\Models\ServiceFieldValue;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class DynamicFieldSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Define fields per subcategory ─────────────────────────────────

        $definitions = [

            // Apartments
            'Apartments' => [
                ['name' => 'area_sqm',   'label' => 'المساحة (م²)',       'type' => 'number', 'is_required' => true],
                ['name' => 'rooms',      'label' => 'عدد الغرف',          'type' => 'number', 'is_required' => true],
                ['name' => 'bathrooms',  'label' => 'دورات المياه',        'type' => 'number', 'is_required' => true],
                ['name' => 'floor',      'label' => 'رقم الطابق',          'type' => 'number', 'is_required' => false],
                ['name' => 'furnished',  'label' => 'مفروش',               'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا', 'جزئياً']],
                ['name' => 'parking',    'label' => 'موقف سيارة',          'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'elevator',   'label' => 'مصعد',                'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            // Villas
            'Villas' => [
                ['name' => 'area_sqm',   'label' => 'مساحة البناء (م²)',   'type' => 'number', 'is_required' => true],
                ['name' => 'land_area',  'label' => 'مساحة الأرض (م²)',    'type' => 'number', 'is_required' => false],
                ['name' => 'rooms',      'label' => 'عدد الغرف',           'type' => 'number', 'is_required' => true],
                ['name' => 'bathrooms',  'label' => 'دورات المياه',         'type' => 'number', 'is_required' => false],
                ['name' => 'pool',       'label' => 'مسبح',                'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'garden',     'label' => 'حديقة خاصة',          'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'garage',     'label' => 'كراج',                'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            // Houses
            'Houses' => [
                ['name' => 'area_sqm',   'label' => 'المساحة (م²)',        'type' => 'number', 'is_required' => true],
                ['name' => 'rooms',      'label' => 'عدد الغرف',           'type' => 'number', 'is_required' => false],
                ['name' => 'floors',     'label' => 'عدد الطوابق',         'type' => 'number', 'is_required' => false],
                ['name' => 'garden',     'label' => 'حديقة',               'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            // Offices
            'Offices' => [
                ['name' => 'area_sqm',      'label' => 'المساحة (م²)',       'type' => 'number', 'is_required' => true],
                ['name' => 'floor',         'label' => 'الطابق',             'type' => 'number', 'is_required' => false],
                ['name' => 'meeting_rooms', 'label' => 'قاعات اجتماعات',    'type' => 'number', 'is_required' => false],
                ['name' => 'open_space',    'label' => 'مكتب مفتوح',        'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'furnished',     'label' => 'مفروش',              'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا', 'جزئياً']],
            ],

            // Shops
            'Shops' => [
                ['name' => 'area_sqm',   'label' => 'المساحة (م²)',        'type' => 'number', 'is_required' => true],
                ['name' => 'floor',      'label' => 'الطابق',              'type' => 'select', 'is_required' => false,
                 'options' => ['أرضي', 'أول', 'ثاني', 'قبو']],
                ['name' => 'shopfront',  'label' => 'واجهة زجاجية',        'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            // Warehouses
            'Warehouses' => [
                ['name' => 'area_sqm',       'label' => 'المساحة (م²)',      'type' => 'number', 'is_required' => true],
                ['name' => 'ceiling_height',  'label' => 'ارتفاع السقف (م)', 'type' => 'number', 'is_required' => false],
                ['name' => 'loading_bay',     'label' => 'رصيف تحميل',       'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'power_phase',     'label' => 'نوع الكهرباء',     'type' => 'select', 'is_required' => false,
                 'options' => ['أحادي الطور', 'ثلاثي الأطوار']],
            ],

            // Residential Land
            'Residential Land' => [
                ['name' => 'area_sqm',     'label' => 'المساحة (م²)',         'type' => 'number', 'is_required' => true],
                ['name' => 'road_frontage','label' => 'الواجهة على الشارع (م)','type' => 'number', 'is_required' => false],
                ['name' => 'permit',       'label' => 'رخصة بناء',            'type' => 'select', 'is_required' => false,
                 'options' => ['متاحة', 'قيد الإجراء', 'غير متاحة']],
            ],

            // Agricultural Land
            'Agricultural Land' => [
                ['name' => 'area_dunams',  'label' => 'المساحة (دونم)',       'type' => 'number', 'is_required' => true],
                ['name' => 'water_source', 'label' => 'مصدر المياه',          'type' => 'select', 'is_required' => false,
                 'options' => ['بئر', 'نهر', 'شبكة عامة', 'أمطار']],
                ['name' => 'current_use',  'label' => 'الاستخدام الحالي',     'type' => 'text',   'is_required' => false],
            ],

            // Commercial Land
            'Commercial Land' => [
                ['name' => 'area_sqm',     'label' => 'المساحة (م²)',           'type' => 'number', 'is_required' => true],
                ['name' => 'road_frontage','label' => 'الواجهة على الشارع (م)', 'type' => 'number', 'is_required' => false],
                ['name' => 'zoning',       'label' => 'التصنيف العمراني',       'type' => 'select', 'is_required' => false,
                 'options' => ['تجاري', 'سكني تجاري', 'صناعي']],
            ],

            // Brokerage
            'Brokerage' => [
                ['name' => 'commission_pct', 'label' => 'نسبة العمولة (%)',  'type' => 'number', 'is_required' => false],
                ['name' => 'contract_type',  'label' => 'نوع التعاقد',       'type' => 'select', 'is_required' => false,
                 'options' => ['حصري', 'مفتوح']],
                ['name' => 'coverage_areas', 'label' => 'المناطق المغطاة',   'type' => 'text',   'is_required' => false],
            ],

            // Property Valuation
            'Property Valuation' => [
                ['name' => 'report_days',    'label' => 'مدة التقرير (أيام)', 'type' => 'number', 'is_required' => false],
                ['name' => 'bank_accepted',  'label' => 'مقبول للبنوك',       'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'covered_types',  'label' => 'أنواع العقارات',     'type' => 'text',   'is_required' => false],
            ],

            // Interior Design
            'Interior Design' => [
                ['name' => 'max_area_sqm',   'label' => 'أقصى مساحة (م²)',    'type' => 'number', 'is_required' => false],
                ['name' => 'includes_3d',    'label' => 'يشمل تصميم 3D',      'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'timeline_days',  'label' => 'مدة التنفيذ (يوم)',  'type' => 'number', 'is_required' => false],
            ],

            // Painting & Decor
            'Painting & Decor' => [
                ['name' => 'paint_brand',        'label' => 'ماركة الدهان',      'type' => 'text',   'is_required' => false],
                ['name' => 'includes_materials', 'label' => 'يشمل المواد',       'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'min_area_sqm',       'label' => 'أدنى مساحة (م²)',  'type' => 'number', 'is_required' => false],
            ],
        ];

        // Insert field definitions
        $fieldMap = []; // subcategory_name => [field_name => DynamicField]

        foreach ($definitions as $subName => $fields) {
            $sub = Subcategory::where('name_en', $subName)->first();
            if (!$sub) continue;

            $fieldMap[$subName] = [];
            foreach ($fields as $def) {
                $field = DynamicField::firstOrCreate(
                    ['subcategory_id' => $sub->id, 'name' => $def['name']],
                    [
                        'category_id'  => $sub->category_id,
                        'label'        => $def['label'],
                        'type'         => $def['type'],
                        'is_required'  => $def['is_required'],
                        'options'      => $def['options'] ?? null,
                    ]
                );
                $fieldMap[$subName][$def['name']] = $field->id;
            }
        }

        // ── 2. Assign values to each seeded service ───────────────────────────

        $svc = fn(string $title) => Service::where('title', $title)->first();
        $fid = fn(string $sub, string $name) => $fieldMap[$sub][$name] ?? null;
        $val = function(int $serviceId, ?int $fieldId, string $value) {
            if (!$fieldId) return;
            ServiceFieldValue::updateOrCreate(
                ['service_id' => $serviceId, 'dynamic_field_id' => $fieldId],
                ['value' => $value]
            );
        };

        // ── Modern 3-Bedroom Apartment — Mezzeh ──────────────────────────────
        if ($s = $svc('Modern 3-Bedroom Apartment — Mezzeh')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '145');
            $val($s->id, $fid('Apartments', 'rooms'),     '3');
            $val($s->id, $fid('Apartments', 'bathrooms'), '2');
            $val($s->id, $fid('Apartments', 'floor'),     '5');
            $val($s->id, $fid('Apartments', 'furnished'), 'جزئياً');
            $val($s->id, $fid('Apartments', 'parking'),   'نعم');
            $val($s->id, $fid('Apartments', 'elevator'),  'نعم');
        }

        // ── Furnished Studio — Kafr Sousa ────────────────────────────────────
        if ($s = $svc('Furnished Studio — Kafr Sousa')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '48');
            $val($s->id, $fid('Apartments', 'rooms'),     '1');
            $val($s->id, $fid('Apartments', 'bathrooms'), '1');
            $val($s->id, $fid('Apartments', 'floor'),     '3');
            $val($s->id, $fid('Apartments', 'furnished'), 'نعم');
            $val($s->id, $fid('Apartments', 'parking'),   'لا');
            $val($s->id, $fid('Apartments', 'elevator'),  'نعم');
        }

        // ── Full Property Brokerage Service — Damascus ────────────────────────
        if ($s = $svc('Full Property Brokerage Service — Damascus')) {
            $val($s->id, $fid('Brokerage', 'commission_pct'), '2.5');
            $val($s->id, $fid('Brokerage', 'contract_type'),  'مفتوح');
            $val($s->id, $fid('Brokerage', 'coverage_areas'), 'مزة، كفر سوسة، أبو رمانة، المالكي، دمشق الجديدة');
        }

        // ── Luxury Villa with Garden — Aleppo ────────────────────────────────
        if ($s = $svc('Luxury Villa with Garden — Aleppo')) {
            $val($s->id, $fid('Villas', 'area_sqm'),  '380');
            $val($s->id, $fid('Villas', 'land_area'), '800');
            $val($s->id, $fid('Villas', 'rooms'),     '5');
            $val($s->id, $fid('Villas', 'bathrooms'), '4');
            $val($s->id, $fid('Villas', 'pool'),      'نعم');
            $val($s->id, $fid('Villas', 'garden'),    'نعم');
            $val($s->id, $fid('Villas', 'garage'),    'نعم');
        }

        // ── Office Space in Business District — Aleppo ────────────────────────
        if ($s = $svc('Office Space in Business District — Aleppo')) {
            $val($s->id, $fid('Offices', 'area_sqm'),      '120');
            $val($s->id, $fid('Offices', 'floor'),         '4');
            $val($s->id, $fid('Offices', 'meeting_rooms'), '2');
            $val($s->id, $fid('Offices', 'open_space'),    'لا');
            $val($s->id, $fid('Offices', 'furnished'),     'نعم');
        }

        // ── Residential Plot 600 m² — North Aleppo ───────────────────────────
        if ($s = $svc('Residential Plot 600 m² — North Aleppo')) {
            $val($s->id, $fid('Residential Land', 'area_sqm'),      '600');
            $val($s->id, $fid('Residential Land', 'road_frontage'), '20');
            $val($s->id, $fid('Residential Land', 'permit'),        'متاحة');
        }

        // ── Sea-View Apartment — Latakia Corniche ─────────────────────────────
        if ($s = $svc('Sea-View Apartment — Latakia Corniche')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '110');
            $val($s->id, $fid('Apartments', 'rooms'),     '2');
            $val($s->id, $fid('Apartments', 'bathrooms'), '2');
            $val($s->id, $fid('Apartments', 'floor'),     '7');
            $val($s->id, $fid('Apartments', 'furnished'), 'جزئياً');
            $val($s->id, $fid('Apartments', 'parking'),   'نعم');
            $val($s->id, $fid('Apartments', 'elevator'),  'نعم');
        }

        // ── Coastal Chalet for Rent — Blue Beach ─────────────────────────────
        if ($s = $svc('Coastal Chalet for Rent — Blue Beach')) {
            $val($s->id, $fid('Houses', 'area_sqm'), '180');
            $val($s->id, $fid('Houses', 'rooms'),    '4');
            $val($s->id, $fid('Houses', 'floors'),   '2');
            $val($s->id, $fid('Houses', 'garden'),   'نعم');
        }

        // ── Full Interior Design Package — Latakia ────────────────────────────
        if ($s = $svc('Full Interior Design Package — Latakia')) {
            $val($s->id, $fid('Interior Design', 'max_area_sqm'),  '200');
            $val($s->id, $fid('Interior Design', 'includes_3d'),   'نعم');
            $val($s->id, $fid('Interior Design', 'timeline_days'), '60');
        }

        // ── Agricultural Land 5 Dunams — Tartus ──────────────────────────────
        if ($s = $svc('Agricultural Land 5 Dunams — Tartus')) {
            $val($s->id, $fid('Agricultural Land', 'area_dunams'),  '5');
            $val($s->id, $fid('Agricultural Land', 'water_source'), 'نهر');
            $val($s->id, $fid('Agricultural Land', 'current_use'),  'زراعة حمضيات');
        }

        // ── Ground-Floor Retail Shop — Tartus Souq ───────────────────────────
        if ($s = $svc('Ground-Floor Retail Shop — Tartus Souq')) {
            $val($s->id, $fid('Shops', 'area_sqm'),  '80');
            $val($s->id, $fid('Shops', 'floor'),     'أرضي');
            $val($s->id, $fid('Shops', 'shopfront'), 'نعم');
        }

        // ── Certified Property Valuation — Tartus Region ─────────────────────
        if ($s = $svc('Certified Property Valuation — Tartus Region')) {
            $val($s->id, $fid('Property Valuation', 'report_days'),   '5');
            $val($s->id, $fid('Property Valuation', 'bank_accepted'), 'نعم');
            $val($s->id, $fid('Property Valuation', 'covered_types'), 'سكني، تجاري، زراعي');
        }

        // ── 2-Bedroom Apartment — New Homs ───────────────────────────────────
        if ($s = $svc('2-Bedroom Apartment — New Homs')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '95');
            $val($s->id, $fid('Apartments', 'rooms'),     '2');
            $val($s->id, $fid('Apartments', 'bathrooms'), '1');
            $val($s->id, $fid('Apartments', 'floor'),     '2');
            $val($s->id, $fid('Apartments', 'furnished'), 'لا');
            $val($s->id, $fid('Apartments', 'parking'),   'لا');
            $val($s->id, $fid('Apartments', 'elevator'),  'لا');
        }

        // ── Professional Painting & Finishing — Homs ─────────────────────────
        if ($s = $svc('Professional Painting & Finishing — Homs')) {
            $val($s->id, $fid('Painting & Decor', 'paint_brand'),        'Jotun / Dulux');
            $val($s->id, $fid('Painting & Decor', 'includes_materials'), 'نعم');
            $val($s->id, $fid('Painting & Decor', 'min_area_sqm'),       '50');
        }

        // ── Industrial Warehouse 500 m² — Homs Industrial Zone ───────────────
        if ($s = $svc('Industrial Warehouse 500 m² — Homs Industrial Zone')) {
            $val($s->id, $fid('Warehouses', 'area_sqm'),       '500');
            $val($s->id, $fid('Warehouses', 'ceiling_height'), '6');
            $val($s->id, $fid('Warehouses', 'loading_bay'),    'نعم');
            $val($s->id, $fid('Warehouses', 'power_phase'),    'ثلاثي الأطوار');
        }

        // ── Commercial Plot on Main Road — Homs ──────────────────────────────
        if ($s = $svc('Commercial Plot on Main Road — Homs')) {
            $val($s->id, $fid('Commercial Land', 'area_sqm'),      '1200');
            $val($s->id, $fid('Commercial Land', 'road_frontage'), '40');
            $val($s->id, $fid('Commercial Land', 'zoning'),        'تجاري');
        }
    }
}
