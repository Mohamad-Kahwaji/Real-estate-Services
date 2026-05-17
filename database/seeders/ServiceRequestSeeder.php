<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Service;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $omar   = User::where('phone', '0933333333')->first();
        $layla  = User::where('phone', '0944444444')->first();
        $khaled = User::where('phone', '0955555555')->first();
        $rania  = User::where('phone', '0966666666')->first();
        $ahmad  = User::where('phone', '0911111111')->first();

        $bAlNour  = Business::where('license_number', 10234)->first();
        $bAleppo  = Business::where('license_number', 20451)->first();
        $bCoastal = Business::where('license_number', 30789)->first();
        $bHoms    = Business::where('license_number', 50672)->first();

        $apartmentDamascus = Service::where('title', 'Modern 3-Bedroom Apartment — Mezzeh')->first();
        $brokerage         = Service::where('title', 'Full Property Brokerage Service — Damascus')->first();
        $villa             = Service::where('title', 'Luxury Villa with Garden — Aleppo')->first();
        $seaView           = Service::where('title', 'Sea-View Apartment — Latakia Corniche')->first();
        $painting          = Service::where('title', 'Professional Painting & Finishing — Homs')->first();
        $valuation         = Service::where('title', 'Certified Property Valuation — Tartus Region')->first();

        $requests = [
            [
                'user_id'        => $omar->id,
                'service_id'     => $apartmentDamascus->id,
                'business_id'    => $bAlNour->id,
                'quantity'       => 1,
                'needed_at'      => now()->addDays(14)->format('Y-m-d'),
                'details'        => 'I am interested in viewing the apartment this month. Please confirm availability.',
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ],
            [
                'user_id'        => $layla->id,
                'service_id'     => $brokerage->id,
                'business_id'    => $bAlNour->id,
                'quantity'       => 1,
                'needed_at'      => now()->addDays(7)->format('Y-m-d'),
                'details'        => 'Looking for a 2-bedroom apartment in Mezzeh or Abu Rummaneh. Budget up to $70,000.',
                'status'         => 'approved',
                'payment_status' => 'unpaid',
            ],
            [
                'user_id'        => $khaled->id,
                'service_id'     => $villa->id,
                'business_id'    => $bAleppo->id,
                'quantity'       => 1,
                'needed_at'      => now()->addDays(30)->format('Y-m-d'),
                'details'        => 'Interested in the villa. Would like a virtual tour first before visiting in person.',
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ],
            [
                'user_id'        => $rania->id,
                'service_id'     => $seaView->id,
                'business_id'    => $bCoastal->id,
                'quantity'       => 1,
                'needed_at'      => now()->addDays(10)->format('Y-m-d'),
                'details'        => 'Planning to relocate to Latakia. Need to finalize within 2 weeks.',
                'status'         => 'approved',
                'payment_status' => 'paid',
            ],
            [
                'user_id'        => $ahmad->id,
                'service_id'     => $painting->id,
                'business_id'    => $bHoms->id,
                'quantity'       => 1,
                'needed_at'      => now()->addDays(5)->format('Y-m-d'),
                'details'        => 'Need painting for a 120 m² apartment. Prefer light neutral colours.',
                'status'         => 'rejected',
                'payment_status' => 'unpaid',
            ],
            [
                'user_id'        => $omar->id,
                'service_id'     => $valuation->id,
                'business_id'    => Business::where('license_number', 41003)->first()->id,
                'quantity'       => 1,
                'needed_at'      => now()->addDays(3)->format('Y-m-d'),
                'details'        => 'Need a certified valuation report for a residential apartment in Tartus for bank financing.',
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ],
        ];

        foreach ($requests as $data) {
            ServiceRequest::create($data);
        }
    }
}
