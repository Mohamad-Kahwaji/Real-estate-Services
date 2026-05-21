<?php

namespace Database\Seeders;

use App\Models\Activetype;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActiveTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Activetype::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $types = [
            ['name' => 'Real Estate'],
            ['name' => 'Construction & Finishing'],
            ['name' => 'Maintenance'],
            ['name' => 'Cleaning'],
            ['name' => 'Transport & Moving'],
            ['name' => 'Technology & Security'],
            ['name' => 'Home Services'],
        ];

        foreach ($types as $type) {
            Activetype::create($type);
        }
    }
}
