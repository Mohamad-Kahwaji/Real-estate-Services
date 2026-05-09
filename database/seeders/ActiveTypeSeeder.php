<?php

namespace Database\Seeders;

use App\Models\Activetype;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActiveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activityTypes = [
            ['name' => 'Real Estate Office'],
            ['name' => 'Real Estate Agent'],
            ['name' => 'Real Estate Developer'],
            ['name' => 'Property Management Company'],
            ['name' => 'Real Estate Marketing Company'],
            ['name' => 'Contractor'],
            ['name' => 'Real Estate Investor'],
            ['name' => 'Property Valuation Company'],
            ['name' => 'Real Estate Consultancy'],
            ['name' => 'Real Estate Services Company'],
        ];

        foreach ($activityTypes as $type) {
            Activetype::create($type);
        }
    }
}
