<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $ahmad  = User::where('phone', '0911111111')->first();
        $sara   = User::where('phone', '0922222222')->first();
        $omar   = User::where('phone', '0933333333')->first();
        $layla  = User::where('phone', '0944444444')->first();
        $rania  = User::where('phone', '0966666666')->first();

        $pairs = [
            [$ahmad->id, 'Sea-View Apartment — Latakia Corniche'],
            [$ahmad->id, 'Luxury Villa with Garden — Aleppo'],
            [$ahmad->id, 'Certified Property Valuation — Tartus Region'],
            [$sara->id,  'Modern 3-Bedroom Apartment — Mezzeh'],
            [$sara->id,  'Full Interior Design Package — Latakia'],
            [$sara->id,  'Professional Painting & Finishing — Homs'],
            [$omar->id,  'Office Space in Business District — Aleppo'],
            [$omar->id,  'Residential Plot 600 m² — North Aleppo'],
            [$omar->id,  'Commercial Plot on Main Road — Homs'],
            [$layla->id, 'Coastal Chalet for Rent — Blue Beach'],
            [$layla->id, 'Sea-View Apartment — Latakia Corniche'],
            [$rania->id, 'Furnished Studio — Kafr Sousa'],
            [$rania->id, '2-Bedroom Apartment — New Homs'],
        ];

        foreach ($pairs as [$userId, $title]) {
            $service = Service::where('title', $title)->first();
            if (!$service) continue;

            DB::table('favorites')->insertOrIgnore([
                'user_id'    => $userId,
                'service_id' => $service->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
