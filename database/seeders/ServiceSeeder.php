<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Category;
use App\Models\Service;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $res  = Category::where('name_en', 'Residential')->first();
        $com  = Category::where('name_en', 'Commercial')->first();
        $land = Category::where('name_en', 'Land & Plots')->first();
        $svc  = Category::where('name_en', 'Real Estate Services')->first();
        $ren  = Category::where('name_en', 'Renovation & Maintenance')->first();

        $sub = fn(string $name) => Subcategory::where('name_en', $name)->first();

        $bAlNour  = Business::where('license_number', 10234)->first();
        $bAleppo  = Business::where('license_number', 20451)->first();
        $bCoastal = Business::where('license_number', 30789)->first();
        $bTartus  = Business::where('license_number', 41003)->first();
        $bHoms    = Business::where('license_number', 50672)->first();

        $services = [

            // ── Al Nour Damascus ─────────────────────────────────────────────
            [
                'business_id'    => $bAlNour->id,
                'category_id'    => $res->id,
                'subcategory_id' => $sub('Apartments')->id,
                'title'          => 'Modern 3-Bedroom Apartment — Mezzeh',
                'description'    => 'Spacious 3-bedroom apartment in the upscale Mezzeh district of Damascus. Features a large balcony, modern kitchen, and underground parking. Close to embassies and international schools.',
                'quantity'       => 1,
                'services_type'  => 'sale',
                'price_usd'      => 85000.00,
                'price_syp'      => 170000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&q=80',
                'latitude'       => 33.5080,
                'longitude'      => 36.2601,
            ],
            [
                'business_id'    => $bAlNour->id,
                'category_id'    => $res->id,
                'subcategory_id' => $sub('Apartments')->id,
                'title'          => 'Furnished Studio — Kafr Sousa',
                'description'    => 'Fully furnished studio apartment in Kafr Sousa, Damascus. Ideal for singles or couples. Includes all white goods and fast internet connection. Available for long-term rent.',
                'quantity'       => 3,
                'services_type'  => 'rent',
                'price_usd'      => 350.00,
                'price_syp'      => 700000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&q=80',
                'latitude'       => 33.4987,
                'longitude'      => 36.2714,
            ],
            [
                'business_id'    => $bAlNour->id,
                'category_id'    => $svc->id,
                'subcategory_id' => $sub('Brokerage')->id,
                'title'          => 'Full Property Brokerage Service — Damascus',
                'description'    => 'End-to-end brokerage service covering buyer representation, property tours, price negotiation, and contract preparation. Serving all Damascus neighbourhoods.',
                'quantity'       => 10,
                'services_type'  => 'sale',
                'price_usd'      => 500.00,
                'price_syp'      => 1000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1507089947368-19c1da9775ae?w=800&q=80',
                'latitude'       => 33.5138,
                'longitude'      => 36.2765,
            ],

            // ── Aleppo Agency ─────────────────────────────────────────────────
            [
                'business_id'    => $bAleppo->id,
                'category_id'    => $res->id,
                'subcategory_id' => $sub('Villas')->id,
                'title'          => 'Luxury Villa with Garden — Aleppo',
                'description'    => 'Stunning 5-bedroom villa in a gated compound on the outskirts of Aleppo. Features a private garden, swimming pool, and double garage. Ideal for families seeking privacy and comfort.',
                'quantity'       => 1,
                'services_type'  => 'sale',
                'price_usd'      => 220000.00,
                'price_syp'      => 440000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1613977257363-707ba9348227?w=800&q=80',
                'latitude'       => 36.2150,
                'longitude'      => 37.1580,
            ],
            [
                'business_id'    => $bAleppo->id,
                'category_id'    => $com->id,
                'subcategory_id' => $sub('Offices')->id,
                'title'          => 'Office Space in Business District — Aleppo',
                'description'    => 'Premium 120 m² office space in central Aleppo, fully fitted with meeting rooms, reception area, and high-speed fibre internet. Suitable for corporate headquarters.',
                'quantity'       => 2,
                'services_type'  => 'rent',
                'price_usd'      => 800.00,
                'price_syp'      => 1600000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=800&q=80',
                'latitude'       => 36.1980,
                'longitude'      => 37.1290,
            ],
            [
                'business_id'    => $bAleppo->id,
                'category_id'    => $land->id,
                'subcategory_id' => $sub('Residential Land')->id,
                'title'          => 'Residential Plot 600 m² — North Aleppo',
                'description'    => 'Ready-to-build residential plot of 600 m² in a newly developed area north of Aleppo. All utilities available at boundary. No construction restrictions.',
                'quantity'       => 1,
                'services_type'  => 'sale',
                'price_usd'      => 45000.00,
                'price_syp'      => 90000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800&q=80',
                'latitude'       => 36.2450,
                'longitude'      => 37.1100,
            ],

            // ── Coastal Homes Latakia ─────────────────────────────────────────
            [
                'business_id'    => $bCoastal->id,
                'category_id'    => $res->id,
                'subcategory_id' => $sub('Apartments')->id,
                'title'          => 'Sea-View Apartment — Latakia Corniche',
                'description'    => 'Beautiful 2-bedroom apartment on the 7th floor with unobstructed sea views. Located on Latakia Corniche, walking distance to restaurants and the beach.',
                'quantity'       => 2,
                'services_type'  => 'sale',
                'price_usd'      => 72000.00,
                'price_syp'      => 144000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1567767292278-a4f21aa2d36e?w=800&q=80',
                'latitude'       => 35.5450,
                'longitude'      => 35.7830,
            ],
            [
                'business_id'    => $bCoastal->id,
                'category_id'    => $res->id,
                'subcategory_id' => $sub('Houses')->id,
                'title'          => 'Coastal Chalet for Rent — Blue Beach',
                'description'    => 'Private chalet 150 m from the sea, sleeping 6. Fully furnished with a rooftop terrace, outdoor barbecue area, and dedicated parking. Available weekly or monthly.',
                'quantity'       => 5,
                'services_type'  => 'rent',
                'price_usd'      => 1200.00,
                'price_syp'      => 2400000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1499793983690-e29da59ef1c2?w=800&q=80',
                'latitude'       => 35.5180,
                'longitude'      => 35.7720,
            ],
            [
                'business_id'    => $bCoastal->id,
                'category_id'    => $ren->id,
                'subcategory_id' => $sub('Interior Design')->id,
                'title'          => 'Full Interior Design Package — Latakia',
                'description'    => 'Complete interior design service for residential properties up to 200 m². Includes 3D visualisation, material selection, supervision, and final handover. 60-day project timeline.',
                'quantity'       => 8,
                'services_type'  => 'sale',
                'price_usd'      => 3500.00,
                'price_syp'      => 7000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800&q=80',
                'latitude'       => 35.5317,
                'longitude'      => 35.7916,
            ],

            // ── Tartus Investments ────────────────────────────────────────────
            [
                'business_id'    => $bTartus->id,
                'category_id'    => $land->id,
                'subcategory_id' => $sub('Agricultural Land')->id,
                'title'          => 'Agricultural Land 5 Dunams — Tartus',
                'description'    => 'Fertile 5-dunam agricultural plot with year-round water supply near Tartus. Historically used for citrus farming. Clear title, ready for transfer.',
                'quantity'       => 1,
                'services_type'  => 'sale',
                'price_usd'      => 38000.00,
                'price_syp'      => 76000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=800&q=80',
                'latitude'       => 34.9100,
                'longitude'      => 35.9020,
            ],
            [
                'business_id'    => $bTartus->id,
                'category_id'    => $com->id,
                'subcategory_id' => $sub('Shops')->id,
                'title'          => 'Ground-Floor Retail Shop — Tartus Souq',
                'description'    => 'Prime 80 m² ground-floor retail space in Tartus city centre near the main souq. High foot traffic, glass shopfront, storage room at the rear.',
                'quantity'       => 1,
                'services_type'  => 'rent',
                'price_usd'      => 600.00,
                'price_syp'      => 1200000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=800&q=80',
                'latitude'       => 34.8930,
                'longitude'      => 35.8810,
            ],
            [
                'business_id'    => $bTartus->id,
                'category_id'    => $svc->id,
                'subcategory_id' => $sub('Property Valuation')->id,
                'title'          => 'Certified Property Valuation — Tartus Region',
                'description'    => 'Officially certified property valuation for residential, commercial, and agricultural assets in Tartus governorate. Report issued within 5 business days. Accepted by all Syrian banks.',
                'quantity'       => 20,
                'services_type'  => 'sale',
                'price_usd'      => 150.00,
                'price_syp'      => 300000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800&q=80',
                'latitude'       => 34.8958,
                'longitude'      => 35.8858,
            ],

            // ── Homs Management ───────────────────────────────────────────────
            [
                'business_id'    => $bHoms->id,
                'category_id'    => $res->id,
                'subcategory_id' => $sub('Apartments')->id,
                'title'          => '2-Bedroom Apartment — New Homs',
                'description'    => 'Newly renovated 2-bedroom apartment in the New Homs residential complex. Central heating, double-glazed windows, and a communal garden. Ideal for young families.',
                'quantity'       => 4,
                'services_type'  => 'rent',
                'price_usd'      => 280.00,
                'price_syp'      => 560000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?w=800&q=80',
                'latitude'       => 34.7400,
                'longitude'      => 36.7200,
            ],
            [
                'business_id'    => $bHoms->id,
                'category_id'    => $ren->id,
                'subcategory_id' => $sub('Painting & Decor')->id,
                'title'          => 'Professional Painting & Finishing — Homs',
                'description'    => 'High-quality interior and exterior painting service for apartments and villas in Homs. Using premium European paints. Free colour consultation included.',
                'quantity'       => 15,
                'services_type'  => 'sale',
                'price_usd'      => 800.00,
                'price_syp'      => 1600000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1562259949-e8e7689d7828?w=800&q=80',
                'latitude'       => 34.7280,
                'longitude'      => 36.7050,
            ],
            [
                'business_id'    => $bHoms->id,
                'category_id'    => $com->id,
                'subcategory_id' => $sub('Warehouses')->id,
                'title'          => 'Industrial Warehouse 500 m² — Homs Industrial Zone',
                'description'    => 'Large 500 m² warehouse in the Homs industrial zone with 6 m ceiling height, loading bay, 3-phase electricity, and 24-hour security. Suitable for storage or light manufacturing.',
                'quantity'       => 2,
                'services_type'  => 'rent',
                'price_usd'      => 1800.00,
                'price_syp'      => 3600000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=800&q=80',
                'latitude'       => 34.7500,
                'longitude'      => 36.7400,
            ],
            [
                'business_id'    => $bHoms->id,
                'category_id'    => $land->id,
                'subcategory_id' => $sub('Commercial Land')->id,
                'title'          => 'Commercial Plot on Main Road — Homs',
                'description'    => 'Strategic 1200 m² commercial plot with 40 m frontage on a main arterial road in Homs. Zoned for commercial or mixed-use development. All permits available.',
                'quantity'       => 1,
                'services_type'  => 'sale',
                'price_usd'      => 95000.00,
                'price_syp'      => 190000000.00,
                'currency'       => 'USD',
                'status'         => 'approved',
                'image'          => 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=800&q=80',
                'latitude'       => 34.7180,
                'longitude'      => 36.7320,
            ],
        ];

        foreach ($services as $data) {
            Service::updateOrCreate(
                ['title' => $data['title'], 'business_id' => $data['business_id']],
                $data
            );
        }
    }
}
