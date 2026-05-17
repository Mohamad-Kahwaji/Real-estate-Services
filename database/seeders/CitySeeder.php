<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name_ar' => 'دمشق',       'name_en' => 'Damascus'],
            ['name_ar' => 'حلب',         'name_en' => 'Aleppo'],
            ['name_ar' => 'اللاذقية',    'name_en' => 'Latakia'],
            ['name_ar' => 'طرطوس',       'name_en' => 'Tartus'],
            ['name_ar' => 'حمص',         'name_en' => 'Homs'],
            ['name_ar' => 'حماه',        'name_en' => 'Hama'],
            ['name_ar' => 'دير الزور',   'name_en' => 'Deir ez-Zor'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(['name_en' => $city['name_en']], $city);
        }
    }
}
