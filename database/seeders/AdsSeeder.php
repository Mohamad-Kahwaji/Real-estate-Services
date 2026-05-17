<?php

namespace Database\Seeders;

use App\Models\Ads;
use Illuminate\Database\Seeder;

class AdsSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'title'       => 'Premium Damascus Properties – Summer 2026',
                'description' => 'Discover exclusive apartments and villas in Mezzeh, Kafr Sousa, and Abu Rummaneh. Prices starting from $55,000. Limited units available.',
                'image'       => 'https://picsum.photos/seed/ad1/800/400',
                'is_active'   => true,
                'created_at'  => now()->subDays(3),
                'updated_at'  => now()->subDays(3),
            ],
            [
                'title'       => 'Coastal Latakia Homes – Sea-View Units',
                'description' => 'Live steps from the Mediterranean. New residential complex on the Latakia Corniche featuring 1–3 bedroom units with panoramic sea views.',
                'image'       => 'https://picsum.photos/seed/ad2/800/400',
                'is_active'   => true,
                'created_at'  => now()->subDays(7),
                'updated_at'  => now()->subDays(7),
            ],
            [
                'title'       => 'Invest in Aleppo Real Estate – High ROI',
                'description' => 'Commercial and residential investment opportunities in revitalised Aleppo districts. Projected rental yield of 8–12% annually. Contact our investment team today.',
                'image'       => 'https://picsum.photos/seed/ad3/800/400',
                'is_active'   => true,
                'created_at'  => now()->subDays(14),
                'updated_at'  => now()->subDays(14),
            ],
            [
                'title'       => 'Tartus Land Bank – Agricultural & Commercial Plots',
                'description' => 'Secure your future with fertile agricultural land and prime commercial plots in Tartus. Clear titles, immediate transfer, flexible payment plans available.',
                'image'       => 'https://picsum.photos/seed/ad4/800/400',
                'is_active'   => true,
                'created_at'  => now()->subDays(10),
                'updated_at'  => now()->subDays(10),
            ],
            [
                'title'       => 'Homs Industrial Zone – Warehouse & Logistics Spaces',
                'description' => 'Strategically located warehouses and logistics hubs in the Homs Industrial Zone. Sizes from 200 m² to 2,000 m². 3-phase power, loading bays, 24/7 security.',
                'image'       => 'https://picsum.photos/seed/ad5/800/400',
                'is_active'   => true,
                'created_at'  => now()->subDays(5),
                'updated_at'  => now()->subDays(5),
            ],
            [
                'title'       => 'Free Property Valuation – Limited Time Offer',
                'description' => 'Get a certified property valuation report at no charge this month. Valid for residential and commercial properties across all Syrian governorates. Book your slot now.',
                'image'       => 'https://picsum.photos/seed/ad6/800/400',
                'is_active'   => false,
                'created_at'  => now()->subDays(30),
                'updated_at'  => now()->subDays(30),
            ],
        ];

        foreach ($ads as $data) {
            Ads::firstOrCreate(['title' => $data['title']], $data);
        }
    }
}
