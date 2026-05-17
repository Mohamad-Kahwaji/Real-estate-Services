<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $ahmad  = User::where('phone', '0911111111')->first();
        $sara   = User::where('phone', '0922222222')->first();
        $omar   = User::where('phone', '0933333333')->first();
        $layla  = User::where('phone', '0944444444')->first();
        $khaled = User::where('phone', '0955555555')->first();
        $rania  = User::where('phone', '0966666666')->first();

        // Services reported (must not belong to the reporter's business)
        $villa       = Service::where('title', 'Luxury Villa with Garden — Aleppo')->first();
        $seaView     = Service::where('title', 'Sea-View Apartment — Latakia Corniche')->first();
        $warehouse   = Service::where('title', 'Industrial Warehouse 500 m² — Homs Industrial Zone')->first();
        $chalet      = Service::where('title', 'Coastal Chalet for Rent — Blue Beach')->first();
        $plot        = Service::where('title', 'Residential Plot 600 m² — North Aleppo')->first();
        $valuation   = Service::where('title', 'Certified Property Valuation — Tartus Region')->first();
        $painting    = Service::where('title', 'Professional Painting & Finishing — Homs')->first();
        $officeAl    = Service::where('title', 'Office Space in Business District — Aleppo')->first();
        $apartDam    = Service::where('title', 'Modern 3-Bedroom Apartment — Mezzeh')->first();
        $agriLand    = Service::where('title', 'Agricultural Land 5 Dunams — Tartus')->first();

        $reports = [
            [
                'user_id'    => $rania->id,
                'service_id' => $villa->id,
                'reason'     => 'Misleading Information',
                'message'    => 'The listing states 5 bedrooms but photos clearly show only 3. The price also does not match what was discussed over the phone with the agent.',
                'status'     => 'pending',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id'    => $ahmad->id,
                'service_id' => $seaView->id,
                'reason'     => 'Fraudulent Listing',
                'message'    => 'I paid a deposit for a viewing but the agent was unreachable afterward. The property may already be sold but the listing is still active.',
                'status'     => 'reviewed',
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id'    => $sara->id,
                'service_id' => $warehouse->id,
                'reason'     => 'Price Manipulation',
                'message'    => 'The price listed is $1,800/month but the owner demanded $2,500 when contacted directly. Significant discrepancy between listing and actual quote.',
                'status'     => 'resolved',
                'created_at' => now()->subDays(10),
            ],
            [
                'user_id'    => $ahmad->id,
                'service_id' => $chalet->id,
                'reason'     => 'Duplicate Listing',
                'message'    => 'This chalet appears under at least three different listings on the platform with different prices. Please consolidate or remove duplicates.',
                'status'     => 'pending',
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id'    => $rania->id,
                'service_id' => $plot->id,
                'reason'     => 'Incorrect Location',
                'message'    => 'The pin on the map shows the plot inside Aleppo city center, but when I visited the coordinates in person the land is actually 15 km outside the city.',
                'status'     => 'reviewed',
                'created_at' => now()->subDays(7),
            ],
            [
                'user_id'    => $khaled->id,
                'service_id' => $valuation->id,
                'reason'     => 'Unlicensed Service',
                'message'    => 'I requested a valuation report but the company could not provide any official license number or certification from the Syrian Real Estate Authority.',
                'status'     => 'pending',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id'    => $layla->id,
                'service_id' => $painting->id,
                'reason'     => 'Poor Quality Work',
                'message'    => 'The painting service was completed but the quality is very poor. Paint is peeling in multiple areas just 2 weeks after application. Provider refuses to respond.',
                'status'     => 'resolved',
                'created_at' => now()->subDays(14),
            ],
            [
                'user_id'    => $omar->id,
                'service_id' => $officeAl->id,
                'reason'     => 'Spam / Repeated Contact',
                'message'    => 'After submitting an inquiry the business called me 12 times in one day. I asked them to stop but they continued. Please warn the business account.',
                'status'     => 'reviewed',
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id'    => $sara->id,
                'service_id' => $apartDam->id,
                'reason'     => 'Property Already Sold',
                'message'    => 'I was told the apartment is available, but after two site visits found out it had been sold weeks ago. The listing was not updated.',
                'status'     => 'pending',
                'created_at' => now()->subHours(18),
            ],
            [
                'user_id'    => $rania->id,
                'service_id' => $agriLand->id,
                'reason'     => 'Ownership Dispute',
                'message'    => 'I am a neighbor of this plot. The land listed here has an ongoing ownership dispute in court and is not legally available for sale at this time.',
                'status'     => 'reviewed',
                'created_at' => now()->subDays(6),
            ],
        ];

        foreach ($reports as $data) {
            Report::firstOrCreate(
                ['user_id' => $data['user_id'], 'service_id' => $data['service_id']],
                $data
            );
        }
    }
}
