<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SuperadminSeeder::class,
            PermissionSeeder::class,
            ActiveTypeSeeder::class,
            CitySeeder::class,
            CategorySeeder::class,
            SubcategorySeeder::class,
            UserSeeder::class,
            BusinessSeeder::class,
            ServiceSeeder::class,
            ServiceRequestSeeder::class,
            FavoriteSeeder::class,
            ReportSeeder::class,
            AdsSeeder::class,
            DynamicFieldSeeder::class,
        ]);
    }
}
