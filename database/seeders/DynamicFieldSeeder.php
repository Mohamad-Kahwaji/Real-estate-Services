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
        $definitions = [

            'Apartments' => [
                ['name' => 'area_sqm',   'label_ar' => 'المساحة (م²)',       'label_en' => 'Area (m²)',          'type' => 'number', 'is_required' => true],
                ['name' => 'rooms',      'label_ar' => 'عدد الغرف',           'label_en' => 'Number of Rooms',    'type' => 'number', 'is_required' => true],
                ['name' => 'bathrooms',  'label_ar' => 'دورات المياه',         'label_en' => 'Bathrooms',          'type' => 'number', 'is_required' => true],
                ['name' => 'floor',      'label_ar' => 'رقم الطابق',           'label_en' => 'Floor Number',       'type' => 'number', 'is_required' => false],
                ['name' => 'furnished',  'label_ar' => 'مفروش',                'label_en' => 'Furnished',          'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا', 'جزئياً']],
                ['name' => 'parking',    'label_ar' => 'موقف سيارة',           'label_en' => 'Parking',            'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'elevator',   'label_ar' => 'مصعد',                 'label_en' => 'Elevator',           'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            'Villas' => [
                ['name' => 'area_sqm',   'label_ar' => 'مساحة البناء (م²)',   'label_en' => 'Building Area (m²)', 'type' => 'number', 'is_required' => true],
                ['name' => 'land_area',  'label_ar' => 'مساحة الأرض (م²)',    'label_en' => 'Land Area (m²)',     'type' => 'number', 'is_required' => false],
                ['name' => 'rooms',      'label_ar' => 'عدد الغرف',            'label_en' => 'Number of Rooms',    'type' => 'number', 'is_required' => true],
                ['name' => 'bathrooms',  'label_ar' => 'دورات المياه',          'label_en' => 'Bathrooms',          'type' => 'number', 'is_required' => false],
                ['name' => 'pool',       'label_ar' => 'مسبح',                 'label_en' => 'Swimming Pool',      'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'garden',     'label_ar' => 'حديقة خاصة',           'label_en' => 'Private Garden',     'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'garage',     'label_ar' => 'كراج',                 'label_en' => 'Garage',             'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            'Houses' => [
                ['name' => 'area_sqm',   'label_ar' => 'المساحة (م²)',         'label_en' => 'Area (m²)',          'type' => 'number', 'is_required' => true],
                ['name' => 'rooms',      'label_ar' => 'عدد الغرف',             'label_en' => 'Number of Rooms',    'type' => 'number', 'is_required' => false],
                ['name' => 'floors',     'label_ar' => 'عدد الطوابق',           'label_en' => 'Number of Floors',   'type' => 'number', 'is_required' => false],
                ['name' => 'garden',     'label_ar' => 'حديقة',                 'label_en' => 'Garden',             'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            'Offices' => [
                ['name' => 'area_sqm',      'label_ar' => 'المساحة (م²)',       'label_en' => 'Area (m²)',          'type' => 'number', 'is_required' => true],
                ['name' => 'floor',         'label_ar' => 'الطابق',              'label_en' => 'Floor',              'type' => 'number', 'is_required' => false],
                ['name' => 'meeting_rooms', 'label_ar' => 'قاعات اجتماعات',     'label_en' => 'Meeting Rooms',      'type' => 'number', 'is_required' => false],
                ['name' => 'open_space',    'label_ar' => 'مكتب مفتوح',         'label_en' => 'Open Space',         'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'furnished',     'label_ar' => 'مفروش',               'label_en' => 'Furnished',          'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا', 'جزئياً']],
            ],

            'Shops' => [
                ['name' => 'area_sqm',   'label_ar' => 'المساحة (م²)',          'label_en' => 'Area (m²)',          'type' => 'number', 'is_required' => true],
                ['name' => 'floor',      'label_ar' => 'الطابق',                 'label_en' => 'Floor',              'type' => 'select', 'is_required' => false,
                 'options' => ['أرضي', 'أول', 'ثاني', 'قبو']],
                ['name' => 'shopfront',  'label_ar' => 'واجهة زجاجية',           'label_en' => 'Glass Facade',       'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
            ],

            'Warehouses' => [
                ['name' => 'area_sqm',        'label_ar' => 'المساحة (م²)',      'label_en' => 'Area (m²)',          'type' => 'number', 'is_required' => true],
                ['name' => 'ceiling_height',   'label_ar' => 'ارتفاع السقف (م)', 'label_en' => 'Ceiling Height (m)', 'type' => 'number', 'is_required' => false],
                ['name' => 'loading_bay',      'label_ar' => 'رصيف تحميل',       'label_en' => 'Loading Bay',        'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'power_phase',      'label_ar' => 'نوع الكهرباء',     'label_en' => 'Power Type',         'type' => 'select', 'is_required' => false,
                 'options' => ['أحادي الطور', 'ثلاثي الأطوار']],
            ],

            'Residential Land' => [
                ['name' => 'area_sqm',      'label_ar' => 'المساحة (م²)',           'label_en' => 'Area (m²)',           'type' => 'number', 'is_required' => true],
                ['name' => 'road_frontage', 'label_ar' => 'الواجهة على الشارع (م)', 'label_en' => 'Road Frontage (m)',   'type' => 'number', 'is_required' => false],
                ['name' => 'permit',        'label_ar' => 'رخصة بناء',              'label_en' => 'Building Permit',     'type' => 'select', 'is_required' => false,
                 'options' => ['متاحة', 'قيد الإجراء', 'غير متاحة']],
            ],

            'Agricultural Land' => [
                ['name' => 'area_dunams',  'label_ar' => 'المساحة (دونم)',          'label_en' => 'Area (Dunam)',        'type' => 'number', 'is_required' => true],
                ['name' => 'water_source', 'label_ar' => 'مصدر المياه',              'label_en' => 'Water Source',        'type' => 'select', 'is_required' => false,
                 'options' => ['بئر', 'نهر', 'شبكة عامة', 'أمطار']],
                ['name' => 'current_use',  'label_ar' => 'الاستخدام الحالي',         'label_en' => 'Current Use',         'type' => 'text',   'is_required' => false],
            ],

            'Commercial Land' => [
                ['name' => 'area_sqm',      'label_ar' => 'المساحة (م²)',           'label_en' => 'Area (m²)',           'type' => 'number', 'is_required' => true],
                ['name' => 'road_frontage', 'label_ar' => 'الواجهة على الشارع (م)', 'label_en' => 'Road Frontage (m)',   'type' => 'number', 'is_required' => false],
                ['name' => 'zoning',        'label_ar' => 'التصنيف العمراني',        'label_en' => 'Urban Zoning',        'type' => 'select', 'is_required' => false,
                 'options' => ['تجاري', 'سكني تجاري', 'صناعي']],
            ],

            'Brokerage' => [
                ['name' => 'commission_pct', 'label_ar' => 'نسبة العمولة (%)',  'label_en' => 'Commission Rate (%)', 'type' => 'number', 'is_required' => false],
                ['name' => 'contract_type',  'label_ar' => 'نوع التعاقد',       'label_en' => 'Contract Type',       'type' => 'select', 'is_required' => false,
                 'options' => ['حصري', 'مفتوح']],
                ['name' => 'coverage_areas', 'label_ar' => 'المناطق المغطاة',   'label_en' => 'Coverage Areas',      'type' => 'text',   'is_required' => false],
            ],

            'Property Valuation' => [
                ['name' => 'report_days',   'label_ar' => 'مدة التقرير (أيام)', 'label_en' => 'Report Duration (days)', 'type' => 'number', 'is_required' => false],
                ['name' => 'bank_accepted', 'label_ar' => 'مقبول للبنوك',       'label_en' => 'Bank Accepted',          'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'covered_types', 'label_ar' => 'أنواع العقارات',     'label_en' => 'Property Types',         'type' => 'text',   'is_required' => false],
            ],

            'Interior Design' => [
                ['name' => 'max_area_sqm',  'label_ar' => 'أقصى مساحة (م²)',   'label_en' => 'Max Area (m²)',          'type' => 'number', 'is_required' => false],
                ['name' => 'includes_3d',   'label_ar' => 'يشمل تصميم 3D',      'label_en' => 'Includes 3D Design',     'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'timeline_days', 'label_ar' => 'مدة التنفيذ (يوم)', 'label_en' => 'Execution Duration (days)', 'type' => 'number', 'is_required' => false],
            ],

            'Painting & Decor' => [
                ['name' => 'paint_brand',        'label_ar' => 'ماركة الدهان',    'label_en' => 'Paint Brand',        'type' => 'text',   'is_required' => false],
                ['name' => 'includes_materials', 'label_ar' => 'يشمل المواد',     'label_en' => 'Includes Materials', 'type' => 'select', 'is_required' => false,
                 'options' => ['نعم', 'لا']],
                ['name' => 'min_area_sqm',       'label_ar' => 'أدنى مساحة (م²)', 'label_en' => 'Min Area (m²)',      'type' => 'number', 'is_required' => false],
            ],
        ];

        $fieldMap = [];

        foreach ($definitions as $subName => $fields) {
            $sub = Subcategory::where('name_en', $subName)->first();
            if (!$sub) continue;

            $fieldMap[$subName] = [];
            foreach ($fields as $def) {
                $field = DynamicField::updateOrCreate(
                    ['subcategory_id' => $sub->id, 'name' => $def['name']],
                    [
                        'category_id' => $sub->category_id,
                        'label'       => $def['label_ar'],
                        'label_ar'    => $def['label_ar'],
                        'label_en'    => $def['label_en'],
                        'type'        => $def['type'],
                        'is_required' => $def['is_required'],
                        'options'     => $def['options'] ?? null,
                    ]
                );
                $fieldMap[$subName][$def['name']] = $field->id;
            }
        }

        // ── Assign values to seeded services ─────────────────────────────────

        $svc = fn(string $title) => Service::where('title', $title)->first();
        $fid = fn(string $sub, string $name) => $fieldMap[$sub][$name] ?? null;
        $val = function(int $serviceId, ?int $fieldId, string $value) {
            if (!$fieldId) return;
            ServiceFieldValue::updateOrCreate(
                ['service_id' => $serviceId, 'dynamic_field_id' => $fieldId],
                ['value' => $value]
            );
        };

        if ($s = $svc('Modern 3-Bedroom Apartment — Mezzeh')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '145');
            $val($s->id, $fid('Apartments', 'rooms'),     '3');
            $val($s->id, $fid('Apartments', 'bathrooms'), '2');
            $val($s->id, $fid('Apartments', 'floor'),     '5');
            $val($s->id, $fid('Apartments', 'furnished'), 'جزئياً');
            $val($s->id, $fid('Apartments', 'parking'),   'نعم');
            $val($s->id, $fid('Apartments', 'elevator'),  'نعم');
        }

        if ($s = $svc('Furnished Studio — Kafr Sousa')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '48');
            $val($s->id, $fid('Apartments', 'rooms'),     '1');
            $val($s->id, $fid('Apartments', 'bathrooms'), '1');
            $val($s->id, $fid('Apartments', 'floor'),     '3');
            $val($s->id, $fid('Apartments', 'furnished'), 'نعم');
            $val($s->id, $fid('Apartments', 'parking'),   'لا');
            $val($s->id, $fid('Apartments', 'elevator'),  'نعم');
        }

        if ($s = $svc('Full Property Brokerage Service — Damascus')) {
            $val($s->id, $fid('Brokerage', 'commission_pct'), '2.5');
            $val($s->id, $fid('Brokerage', 'contract_type'),  'مفتوح');
            $val($s->id, $fid('Brokerage', 'coverage_areas'), 'مزة، كفر سوسة، أبو رمانة، المالكي، دمشق الجديدة');
        }

        if ($s = $svc('Luxury Villa with Garden — Aleppo')) {
            $val($s->id, $fid('Villas', 'area_sqm'),  '380');
            $val($s->id, $fid('Villas', 'land_area'), '800');
            $val($s->id, $fid('Villas', 'rooms'),     '5');
            $val($s->id, $fid('Villas', 'bathrooms'), '4');
            $val($s->id, $fid('Villas', 'pool'),      'نعم');
            $val($s->id, $fid('Villas', 'garden'),    'نعم');
            $val($s->id, $fid('Villas', 'garage'),    'نعم');
        }

        if ($s = $svc('Office Space in Business District — Aleppo')) {
            $val($s->id, $fid('Offices', 'area_sqm'),      '120');
            $val($s->id, $fid('Offices', 'floor'),         '4');
            $val($s->id, $fid('Offices', 'meeting_rooms'), '2');
            $val($s->id, $fid('Offices', 'open_space'),    'لا');
            $val($s->id, $fid('Offices', 'furnished'),     'نعم');
        }

        if ($s = $svc('Residential Plot 600 m² — North Aleppo')) {
            $val($s->id, $fid('Residential Land', 'area_sqm'),      '600');
            $val($s->id, $fid('Residential Land', 'road_frontage'), '20');
            $val($s->id, $fid('Residential Land', 'permit'),        'متاحة');
        }

        if ($s = $svc('Sea-View Apartment — Latakia Corniche')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '110');
            $val($s->id, $fid('Apartments', 'rooms'),     '2');
            $val($s->id, $fid('Apartments', 'bathrooms'), '2');
            $val($s->id, $fid('Apartments', 'floor'),     '7');
            $val($s->id, $fid('Apartments', 'furnished'), 'جزئياً');
            $val($s->id, $fid('Apartments', 'parking'),   'نعم');
            $val($s->id, $fid('Apartments', 'elevator'),  'نعم');
        }

        if ($s = $svc('Coastal Chalet for Rent — Blue Beach')) {
            $val($s->id, $fid('Houses', 'area_sqm'), '180');
            $val($s->id, $fid('Houses', 'rooms'),    '4');
            $val($s->id, $fid('Houses', 'floors'),   '2');
            $val($s->id, $fid('Houses', 'garden'),   'نعم');
        }

        if ($s = $svc('Full Interior Design Package — Latakia')) {
            $val($s->id, $fid('Interior Design', 'max_area_sqm'),  '200');
            $val($s->id, $fid('Interior Design', 'includes_3d'),   'نعم');
            $val($s->id, $fid('Interior Design', 'timeline_days'), '60');
        }

        if ($s = $svc('Agricultural Land 5 Dunams — Tartus')) {
            $val($s->id, $fid('Agricultural Land', 'area_dunams'),  '5');
            $val($s->id, $fid('Agricultural Land', 'water_source'), 'نهر');
            $val($s->id, $fid('Agricultural Land', 'current_use'),  'زراعة حمضيات');
        }

        if ($s = $svc('Ground-Floor Retail Shop — Tartus Souq')) {
            $val($s->id, $fid('Shops', 'area_sqm'),  '80');
            $val($s->id, $fid('Shops', 'floor'),     'أرضي');
            $val($s->id, $fid('Shops', 'shopfront'), 'نعم');
        }

        if ($s = $svc('Certified Property Valuation — Tartus Region')) {
            $val($s->id, $fid('Property Valuation', 'report_days'),   '5');
            $val($s->id, $fid('Property Valuation', 'bank_accepted'), 'نعم');
            $val($s->id, $fid('Property Valuation', 'covered_types'), 'سكني، تجاري، زراعي');
        }

        if ($s = $svc('2-Bedroom Apartment — New Homs')) {
            $val($s->id, $fid('Apartments', 'area_sqm'),  '95');
            $val($s->id, $fid('Apartments', 'rooms'),     '2');
            $val($s->id, $fid('Apartments', 'bathrooms'), '1');
            $val($s->id, $fid('Apartments', 'floor'),     '2');
            $val($s->id, $fid('Apartments', 'furnished'), 'لا');
            $val($s->id, $fid('Apartments', 'parking'),   'لا');
            $val($s->id, $fid('Apartments', 'elevator'),  'لا');
        }

        if ($s = $svc('Professional Painting & Finishing — Homs')) {
            $val($s->id, $fid('Painting & Decor', 'paint_brand'),        'Jotun / Dulux');
            $val($s->id, $fid('Painting & Decor', 'includes_materials'), 'نعم');
            $val($s->id, $fid('Painting & Decor', 'min_area_sqm'),       '50');
        }

        if ($s = $svc('Industrial Warehouse 500 m² — Homs Industrial Zone')) {
            $val($s->id, $fid('Warehouses', 'area_sqm'),       '500');
            $val($s->id, $fid('Warehouses', 'ceiling_height'), '6');
            $val($s->id, $fid('Warehouses', 'loading_bay'),    'نعم');
            $val($s->id, $fid('Warehouses', 'power_phase'),    'ثلاثي الأطوار');
        }

        if ($s = $svc('Commercial Plot on Main Road — Homs')) {
            $val($s->id, $fid('Commercial Land', 'area_sqm'),      '1200');
            $val($s->id, $fid('Commercial Land', 'road_frontage'), '40');
            $val($s->id, $fid('Commercial Land', 'zoning'),        'تجاري');
        }
    }
}
