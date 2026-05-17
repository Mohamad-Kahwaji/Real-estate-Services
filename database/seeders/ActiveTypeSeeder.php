<?php

namespace Database\Seeders;

use App\Models\Activetype;
use Illuminate\Database\Seeder;

class ActiveTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            // ── Real Estate ───────────────────────────────────────────────────
            ['name' => 'Real Estate Office'],
            ['name' => 'Real Estate Agent'],
            ['name' => 'Real Estate Developer'],
            ['name' => 'Property Management Company'],
            ['name' => 'Real Estate Marketing Company'],
            ['name' => 'Real Estate Investor'],
            ['name' => 'Property Valuation Company'],
            ['name' => 'Real Estate Consultancy'],

            // ── Renovation & Construction ─────────────────────────────────────
            ['name' => 'General Contractor'],
            ['name' => 'Interior Design Company'],
            ['name' => 'Painting & Decoration Company'],
            ['name' => 'Electrical Works Company'],
            ['name' => 'Plumbing Company'],
            ['name' => 'Aluminum & Metal Works Company'],

            // ── Maintenance ───────────────────────────────────────────────────
            ['name' => 'General Maintenance Company'],
            ['name' => 'AC Maintenance Company'],
            ['name' => 'Elevator Maintenance Company'],
            ['name' => 'Home Appliance Repair Company'],

            // ── Cleaning ──────────────────────────────────────────────────────
            ['name' => 'Home & Office Cleaning Company'],
            ['name' => 'Specialized Cleaning Company'],

            // ── Moving & Transport ────────────────────────────────────────────
            ['name' => 'Furniture Moving Company'],
            ['name' => 'Goods Transport Company'],

            // ── Systems & Technology ──────────────────────────────────────────
            ['name' => 'Security Systems & CCTV Company'],
            ['name' => 'Networking & Technology Company'],
            ['name' => 'Smart Home Company'],

            // ── Home Services ─────────────────────────────────────────────────
            ['name' => 'Integrated Home Services Company'],
            ['name' => 'Pest Control Company'],
            ['name' => 'Landscaping Company'],
        ];

        foreach ($types as $type) {
            Activetype::firstOrCreate(['name' => $type['name']]);
        }
    }
}
