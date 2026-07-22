<?php

namespace Database\Seeders;

use App\Models\Activetype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ActiveTypeSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Activetype::truncate();
        Schema::enableForeignKeyConstraints();

        $types = [
            ['name' => 'Real Estate',             'name_ar' => 'عقارات'],
            ['name' => 'Construction & Finishing', 'name_ar' => 'بناء وتشطيب'],
            ['name' => 'Maintenance',              'name_ar' => 'صيانة'],
            ['name' => 'Cleaning',                 'name_ar' => 'تنظيف'],
            ['name' => 'Transport & Moving',       'name_ar' => 'نقل وتحريك'],
            ['name' => 'Technology & Security',    'name_ar' => 'تقنية وأمن'],
            ['name' => 'Home Services',            'name_ar' => 'خدمات منزلية'],
        ];

        foreach ($types as $type) {
            Activetype::create($type);
        }
    }
}
